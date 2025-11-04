<?php

/**
 * Returns the current script's filename, escaped for HTML output.
 * This helps prevent XSS attacks when using $_SERVER["PHP_SELF"] in forms.
 *
 * @return string The HTML-escaped PHP_SELF value.
 */
function selfProtection(): string {
    try {
        if (!isset($_SERVER["PHP_SELF"])) {
            handle_fatal_error("PHP_SELF not set in server variables", getCurrentErrorFile(), getCurrentErrorLine());
            return '';
        }
        return htmlspecialchars($_SERVER["PHP_SELF"]);
    } catch (Throwable $e) {
        handle_fatal_error("Error in selfProtection: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
        return '';
    }
}

/**
 * Validates a given URL string using a comprehensive regular expression.
 *
 * @param string $url The URL string to validate.
 * @return bool True if the URL matches the regex, false otherwise.
 */
function validateUrlRegex(string $url): bool {
    try {
        $regex = '/^(?:(?:https?|ftp):\/\/)?(?:www\.)?(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9](?:\/[^ ]*)?$/i';
        return preg_match($regex, $url) === 1;
    } catch (Throwable $e) {
        handle_fatal_error("Error validating URL: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
        return false;
    }
}

/**
 * Validates an email address using PHP's built-in filter_var function.
 *
 * @param string $email The email address string to validate.
 * @return bool True if the email is valid, false otherwise.
 */
function validateEmailBasic(string $email): bool {
    try {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    } catch (Throwable $e) {
        handle_fatal_error("Error validating email: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
        return false;
    }
}

/**
 * Autoloads classes from specified directories.
 *
 * This function registers an autoloader that will attempt to include class files
 * based on their class name and a given set of base directories. It supports
 * PSR-4 like loading by replacing namespace separators with directory separators.
 *
 * @param array $baseDirs An array of base directories to search for class files.
 *                        Example: ['path/to/modules', 'path/to/libraries']
 * @return void
 */
// function autoloadClassesFromModules(array $baseDirs): void
// {
//     spl_autoload_register(function ($className) use ($baseDirs) {
//         // Convert namespace separators to directory separators
//         $className = ltrim($className, '\\');
//         $fileName  = '';
//         $lastNsPos = strrpos($className, '\\');
//         if ($lastNsPos) {
//             $namespace = substr($className, 0, $lastNsPos);
//             $className = substr($className, $lastNsPos + 1);
//             $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
//         }
//         $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

//         foreach ($baseDirs as $baseDir) {
//             $file = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
//             if (file_exists($file)) {
//                 require_once $file;
//                 return;
//             }
//         }
//     });
// }

function autoloadClassesFromModules(array $baseDirs): void
{
    spl_autoload_register(function ($className) use ($baseDirs) {
        // Convert namespace separators to directory separators
        $className = ltrim($className, '\\');
        $fileName  = '';
        $lastNsPos = strrpos($className, '\\');
        if ($lastNsPos) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        foreach ($baseDirs as $baseDir) {
            $file = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
            if (file_exists($file)) {
                require_once $file;
                
                // Get the short class name without namespace
                $shortClassName = substr($className, strrpos($className, '\\') !== false ? strrpos($className, '\\') + 1 : 0);
                
                // Create variable name in camelCase
                $variableName = lcfirst($shortClassName);
                
                // Check if the variable doesn't exist and the class exists
                if (!isset($GLOBALS[$variableName]) && class_exists($className)) {
                    try {
                        // Create instance and assign to global variable
                        $GLOBALS[$variableName] = new $className();
                    } catch (Throwable $e) {
                        handle_fatal_error("Failed to instantiate {$className}: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
                    }
                }
                return;
            }
        }
    });
}

/**
 * Handles uploading a file from a temporary location to a specified destination.
 *
 * @param string $source The temporary file path (usually from $_FILES['input_name']['tmp_name']).
 * @param string $destination The full path including the desired filename for the uploaded file.
 * @return bool True if the upload was successful, false otherwise.
 */
function uploadFile(string $source, string $destination): bool
{
    // 1. Check if the source file is actually an uploaded file
    //    This is a crucial security measure!
    if (!is_uploaded_file($source)) {
         handle_fatal_error("uploadDoc Error: Source path '{$source}' is not a valid uploaded file.");
        return false;
    }

    // 2. Ensure the destination directory exists (optional, but good practice)
    $destinationDirectory = dirname($destination);
    if (!is_dir($destinationDirectory)) {
        // Attempt to create the directory recursively
        if (!mkdir($destinationDirectory, 0775, true)) { // Use appropriate permissions
             handle_fatal_error("uploadDoc Error: Failed to create destination directory '{$destinationDirectory}'.");
            return false;
        }
    }

    // 3. Attempt to move the uploaded file
    if (move_uploaded_file($source, $destination)) {
        // Optional: Set permissions on the uploaded file if needed
        // chmod($destination, 0644);
        return true; // Success!
    } else {
         handle_fatal_error("uploadDoc Error: Failed to move uploaded file from '{$source}' to '{$destination}'. Check permissions and paths.");
        return false; // Failed to move the file
    }
}

/**
 * Handles uploading a file from a temporary location to a specified destination.
 *
 * @param string $source The temporary file path (usually from $_FILES['input_name']['tmp_name']).
 * @param string $destination The full path including the desired filename for the uploaded file.
 * @return bool True if the upload was successful, false otherwise.
 */

/**
 * Verifies if an MD5 hash corresponds to the MD5 hash of a number between 1 and 9999.
 *
 * @param string $inputHash The MD5 hash string to check.
 * @return int|false The matching number (1-9999) if found, otherwise false.
 */
function encodeIDMd5(string $inputHash): int|false
{
    // Optional: Basic validation to ensure the input looks like an MD5 hash
    if (!preg_match('/^[a-f0-9]{32}$/i', $inputHash)) {
        // You might want to handle this error differently, e.g., throw an exception
        // For now, we'll return false if the format is incorrect.
         handle_fatal_error("Invalid MD5 hash format provided: " . $inputHash);
        return false;
    }

    // Loop through numbers from 1 to 9999
    for ($number = 1; $number <= 9999999999; $number++) {
        // Calculate the MD5 hash of the current number (cast to string)
        $calculatedHash = md5((string)$number);

        // Compare the calculated hash with the input hash
        if ($calculatedHash === $inputHash) {
            // If a match is found, return the number
            return $number;
        }
    }

    // If the loop completes without finding a match, return false
    return false;
}

/**
 * Reads the contents of a text file into an array, with each line as an element.
 * Empty lines are skipped and whitespace is trimmed from each line.
 *
 * @param string $filePath The path to the text file.
 * @return array|string An array of lines from the file, or an error message string if the file cannot be read.
 */
function readTextToArray($filePath) {
    try {
        // Check if the file exists
        if (!file_exists($filePath)) {
            handle_fatal_error("Error: File not found: " . $filePath);
            return [];
        }
    
        // Read the file into an array, each line as an element
        $programmes = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($programmes === false) {
            handle_fatal_error("Error: Could not read the file: " . $filePath);
            return [];
        }
        
        // Trim whitespace from each line
        return array_map('trim', $programmes);
        
    } catch (Throwable $e) {
        handle_fatal_error("Error reading file: " . $e->getMessage());
        return [];
    }
  }

  
/**
 * Dynamically instantiates classes found in PHP files within a specified directory.
 *
 * This function scans a given directory for PHP files, extracts class names from
 * these filenames, and attempts to instantiate each class. The instantiated objects
 * are then made available as global variables, with the variable name being the
 * lowercase version of the class name.
 *
 * @param string $directory The path to the directory containing the model files.
 * @return void
 */
function instantiateModels(string $directory): void
{
    // Ensure the directory exists and is readable
    if (!is_dir($directory) || !is_readable($directory)) {
         handle_fatal_error("Error: Model directory '{$directory}' not found or not readable.");
        return;
    }

    $modelFiles = glob($directory . '/*.php');

    if ($modelFiles === false) {
         handle_fatal_error("Failed to scan modular directory '{$directory}' for model files.");
        return;
    }

    foreach ($modelFiles as $filePath) {
        $className = pathinfo($filePath, PATHINFO_FILENAME); // Get filename without extension (e.g., CandidateModel)
        $variableName = lcfirst($className); // Convert to camelCase (e.g., candidateModel)

        // Check if the class exists (it should have been included by requireAllPhpFiles or an autoloader)
        if (class_exists($className)) {
            try {
                // Use variable variables to create the instance
                $GLOBALS[$variableName] = new $className();
                // echo "<!-- Instantiated: \${$variableName} = new {$className}() -->\n"; // For debugging
            } catch (Throwable $e) { // Catch any error or exception during instantiation
                 handle_fatal_error("Failed to instantiate {$className} from {$filePath}: " . $e->getMessage());
                $GLOBALS[$variableName] = null; // Set to null on failure
            }
        } else {
             handle_fatal_error("Class {$className} not found for file {$filePath}, skipping instantiation.");
        }
    }
}

?>