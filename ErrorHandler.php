<?php

define('controlFile', "./control/controller.php");
define('functionsFile', "./control/function.php");
define('globalFile', "./control/global.php");
define('connectionFile', "./control/connection.php");
define('actionFile', "./route/action.php");
define('pageFile', "./route/page.php");
define('mainFile', "./route/main.php");
define('SysInfo',"./data/info.php");
define('loadFile',"./route/load.php");

/**
 * Reads and parses the .env configuration file.
 * 
 * This function reads the .env file located one directory up from the current directory
 * and parses it as an INI file. The .env file should contain key-value pairs of
 * environment variables.
 *
 * @return array An associative array containing the parsed environment variables
 */
function env(){
    try {
        $env = parse_ini_file('./.env');
        return $env;
    } catch (Throwable $e) {
        handle_fatal_error("Error reading .env file: " . $e->getMessage());
        return [];
    }
}

/**
 * Gets the current script file where an error occurred.
 *
 * @return string The path to the file where the error originated.
 */
function getCurrentErrorFile(): string {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if (isset($backtrace[1]['file'])) {
        return $backtrace[1]['file'];
    }
    return 'unknown file';
}

/**
 * Gets the line number in the current script file where an error occurred.
 *
 * @return int The line number where the error originated.
 */
function getCurrentErrorLine(): int {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if (isset($backtrace[1]['line'])) {
        return $backtrace[1]['line'];
    }
    return 0;
}


// --- Function to handle fatal errors ---
function handle_fatal_error(string $message, string $file = '', int $line = 0): void {
    // Log the error
    error_log("[" . date('Y-m-d H:i:s') . "] Fatal Error: $message in $file on line $line\n",3, 'error_log.txt');
    
    // Display detailed error information
    echo "<!DOCTYPE html><html><head><title>Application Error</title>";
    echo "<style>
            .error-container { 
                padding: 20px; 
                margin: 20px; 
                border: 1px solid #ff0000; 
                background-color: #ffebeb;
                max-width: 800px; /* Limit width for better readability */
                margin-left: auto;
                margin-right: auto;
            }
            .error-details { 
                font-family: monospace; 
                margin-top: 10px; 
            }
            .header-row {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .header-row img {
                height: 50px;
                margin-right: 15px;
            }
            .header-row h1 {
                margin: 0;
                color: #000; /* Adjust color for better contrast on light background */
                font-size: 28px;
            }
            @media (max-width: 600px) {
                .error-container {
                    margin: 10px;
                    padding: 15px;
                }
                .header-row h1 {
                    font-size: 24px;
                }
            }
          </style>";
    echo "</head><body>";
    echo "<div class='error-container'>";
    echo "<div class='header-row'><img src='logo.png' alt='Rabbitlite4 Logo'><h1>Rabbitlite4</h1></div>";
    echo "<h3>Fatal Error</h3>";
    echo "<div class='error-details'>";
    echo "<strong>Error Message:</strong> " . htmlspecialchars($message) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($file) . "<br>";
    echo "<strong>Line:</strong> " . $line . "<br>";
    echo "<strong>Reference ID:</strong> " . uniqid()." <br>";
    echo "<strong>Date and Time:</strong>".date('Y-m-d H:i:sa') . "<br>";
    echo "</div></div>";
    echo "</body></html>";
    exit;
}

$env = env();
$file = getCurrentErrorFile();
$line = getCurrentErrorLine();

?>