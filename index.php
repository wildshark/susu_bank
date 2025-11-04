<?php
// --- Error Handling Configuration ---
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// --- Define Core File Paths ---
require_once('ErrorHandler.php');
// --- Include Core Files with Error Checking ---
try {
    // Check and require control.php
    if (!file_exists(controlFile) || !is_readable(controlFile)) {
        throw new RuntimeException("Core file ".controlFile." not found or is not readable.");
    }
    require_once(controlFile);
     // check and require connection.php
    if (!file_exists(connectionFile) || !is_readable(connectionFile)) {
        throw new RuntimeException("Core file ".connectionFile." not found or is not readable.");
    }
    require_once(connectionFile);

    // Check and require functions.php
    if (!file_exists(functionsFile) || !is_readable(functionsFile)) {
        throw new RuntimeException("Core file ".functionsFile." not found or is not readable.");
    }
    require_once(functionsFile);
  
    // Check and require global.php
    if (!file_exists(globalFile) || !is_readable(globalFile)) {
        throw new RuntimeException("Core file ".globalFile." not found or is not readable.");
    }
    require_once(globalFile);

    // // Check and require load.php
    // if (!file_exists(loadFile) || !is_readable(loadFile)) {
    //     throw new RuntimeException("Core file ".loadFile." not found or is not readable.");
    // }
    // require_once(loadFile);

    $business = new Business();
    $client = new ClientsAccount();
    $transaction = new Transactions();
    $ledger = new Ledger();
    
    if($env['USER-MODE'] == false){
        require_once(SysInfo);// start system 
    }else{
        if(!isset($_REQUEST['_submit'])){
            if(!isset($_REQUEST['_page'])){
                if(!isset($_REQUEST['_main'])){
                    require_once($template['login']);
                }else{
                    require_once(mainFile);
                }
            }else{
                require_once(pageFile);
            }
        }else{
            require_once(actionFile);
        }
    }
} catch (Throwable $e) { // Catch any error or exception during core file inclusion
    handle_fatal_error("Failed to load core application files.", $e->getFile(), $e->getLine());
}
?>