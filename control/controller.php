<?php

class SystemController
{

    /**
     * Retrieves all PHP files within a specified directory and its subdirectories.
     *
     * @param string $directory The path to the directory to search.
     * @return array An array of file paths to PHP files, or an error message string.
     */
    public function getAllPhpFiles(string $directory): array|string
    {
        try {
            // Check if the directory exists and is readable
            if (!is_dir($directory)) {
            handle_fatal_error("Error: Directory '$directory' does not exist.", getCurrentErrorFile(), getCurrentErrorLine());
            }

            if (!is_readable($directory)) {
            handle_fatal_error("Error: Directory '$directory' is not readable.", getCurrentErrorFile(), getCurrentErrorLine());
            }

            $phpFiles = [];
            $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
                $phpFiles[] = $file->getPathname();
            }
            }

            if (empty($phpFiles)) {
            handle_fatal_error("Error: No PHP files found in directory '$directory'.", getCurrentErrorFile(), getCurrentErrorLine());
            }

            return $phpFiles;

        } catch (\UnexpectedValueException $e) {
            handle_fatal_error("Directory iteration error: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: Failed to read directory structure.";
        } catch (\Exception $e) {
            handle_fatal_error("Unexpected error: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: An unexpected error occurred.";
        }
    }

    // ... (rest of your getAllPhpFilesTemplate method) ...
    /**
     * Retrieves all PHP files within a specified directory and its subdirectories.
     *
     * @param string $directory The path to the directory to search.
     * @return array An array where the file name (without extension) is the key and the file path is the value, or an error message string.
     */
    public function getFilesTemplate(string $directory): array|string
    {
        // Check if the directory exists and is readable.
        if (!is_dir($directory)) {
            handle_fatal_error("Error: Directory '$directory' does not exist.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        if (!is_readable($directory)) {
            handle_fatal_error("Error: Directory '$directory' is not readable.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        $phpFiles = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            // Skip directories (e.g., "." and "..").
            if ($file->isDir()) {
                continue;
            }

            // Check if the file has a .php extension.
            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
                $fileNameWithoutExtension = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $phpFiles[$fileNameWithoutExtension] = $file->getPathname();
            }
        }

        // Check if any PHP files were found.
        if (empty($phpFiles)) {
            handle_fatal_error("Error: No PHP files found in directory '$directory'.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        return $phpFiles;
    }


    // ... (rest of your requireAllPhpFiles method) ...
    /**
     * Requires all PHP files within a specified directory and its subdirectories.
     *
     * @param string $directory The path to the directory to search.
     * @return array|string An array of successfully included file paths, or an error message string.
     */
    public function requireAllPhpFiles(string $directory): array|string
    {
        // Check if the directory exists and is readable.
        if (!is_dir($directory)) {
            handle_fatal_error("Error: Directory '$directory' does not exist.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        if (!is_readable($directory)) {
            handle_fatal_error("Error: Directory '$directory' is not readable.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        $phpFiles = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $successFiles = [];

        foreach ($iterator as $file) {
            // Skip directories (e.g., "." and "..").
            if ($file->isDir()) {
                continue;
            }

            // Check if the file has a .php extension.
            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
                $phpFiles[] = $file->getPathname();
            }
        }

        // Check if any PHP files were found.
        if (empty($phpFiles)) {
            handle_fatal_error("Error: No PHP files found in directory '$directory'.");
        }

        foreach ($phpFiles as $phpFile) {
            try {
                require_once $phpFile;
                $successFiles[] = $phpFile;
            } catch (Throwable $e) { // Use Throwable which catches Errors and Exceptions
                // Consider logging the error instead of returning immediately
                handle_fatal_error("Error: Failed to include file '$phpFile'. Reason: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
                // Depending on requirements, you might want to collect errors and return them all,
                // or throw an exception, or continue trying to include other files.
                // Returning immediately might hide subsequent inclusion failures.
                return "Error: Failed to include file '$phpFile'. Reason: " . $e->getMessage();
            }
        }

        return $successFiles;
    }

    /**
     * Searches for 'autoload.php' files within a directory and its subdirectories and includes them.
     *
     * @param string $directory The path to the directory to search.
     * @return array|string An array of successfully included 'autoload.php' file paths, or an error message string.
     */
    public function requireAllAutoloadFiles(string $directory): array|string
    {
        // Check if the directory exists and is readable.
        if (!is_dir($directory)) {
            handle_fatal_error("Error: Directory '$directory' does not exist.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        if (!is_readable($directory)) {
            handle_fatal_error("Error: Directory '$directory' is not readable.", getCurrentErrorFile(), getCurrentErrorLine());
        }

        $autoloadFiles = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $successFiles = [];

        foreach ($iterator as $file) {
            // Skip directories (e.g., "." and "..").
            if ($file->isDir()) {
                continue;
            }

            // Check if the file is named 'autoload.php'.
            if (strtolower($file->getFilename()) === 'autoload.php') {
                $autoloadFiles[] = $file->getPathname();
            }
        }

        // Check if any 'autoload.php' files were found.
        if (empty($autoloadFiles)) {
            // Changed to log a warning instead of returning it as a string,
            // as this might not be a critical error.
            handle_fatal_error("Warning: No 'autoload.php' files found in directory '$directory'.", getCurrentErrorFile(), getCurrentErrorLine());
            return []; // Return empty array indicating none were found/included
        }

        foreach ($autoloadFiles as $autoloadFile) {
            try {
                require_once $autoloadFile;
                $successFiles[] = $autoloadFile;
            } catch (Throwable $e) { // Use Throwable
                 // Consider logging the error instead of returning immediately
                handle_fatal_error("Error: Failed to include file '$autoloadFile'. Reason: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
                // See comment in requireAllPhpFiles about error handling strategy.
                return "Error: Failed to include file '$autoloadFile'. Reason: " . $e->getMessage();
            }
        }

        return $successFiles;
    }

    /**
     * Creates a SoapClient instance if the server location is accessible.
     *
     * @param string $location The SOAP server location URL. Defaults to 'http://localhost/server/server.php'.
     * @param string $uri The SOAP server URI. Defaults to 'http://localhost/server'.
     * @param int $timeout Connection timeout in seconds. Defaults to 5.
     * @return SoapClient|false A SoapClient instance on success, false otherwise.
     */
    public function serviceFx(
        string $location = 'https://rabbitlite.iquipedigital.com/servfx/server.php',
        string $uri = 'https://rabbitlite.iquipedigital.com/servfx/',
        int $timeout = 5
    ): \SoapClient|false { // Use \SoapClient in type hint for clarity

        // 1. Check if the server location is accessible
        $context = stream_context_create(['http' => ['timeout' => $timeout]]);
        $headers = @get_headers($location, false, $context);

        if ($headers === false || strpos($headers[0], '200 OK') === false) {
            $status = $headers === false ? 'unreachable' : $headers[0];
            handle_fatal_error("servFx Error: SOAP server location '{$location}' is not accessible or did not return a 200 OK status. Status: {$status}", getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        }

        // 2. If accessible, try to initialize the SOAP client
        try {
            // Use \SoapClient directly here
            $client = new \SoapClient(
                null,
                [
                    'location' => $location,
                    'uri'      => $uri,
                    'trace'    => 1,
                    'connection_timeout' => $timeout,
                    'exceptions' => true
                ]
            );
            return $client;
        } catch (\SoapFault $e) { // Use \SoapFault directly here
            handle_fatal_error("servFx SOAP Error: Failed to create SoapClient for URI '{$uri}' at location '{$location}'. Message: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        } catch (\Exception $e) { // Use \Exception directly here
            handle_fatal_error("servFx General Error: Failed to create SoapClient for URI '{$uri}' at location '{$location}'. Message: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        }
    }

} 
// End of SystemController class


?>
