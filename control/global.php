<?php
// Create an instance of control
date_default_timezone_set($env['TIMEZONE']);
$ysController = new SystemController();
autoloadClassesFromModules(['modules','libraries']);
$function = $ysController->requireAllPhpFiles('function');
$template = $ysController->getFilesTemplate('template');

switch ($env['DBTYPE'] ?? null) {
    case 'sqlite':
        Database::$dbType = 'sqlite';
        Database::$dbName = $env['DBNAME'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'access':
        Database::$dbType = 'access';
        Database::$dbName = $env['DBNAME'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'mysql':
        Database::$dbType = 'mysql';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'pgsql':
        Database::$dbType = 'pgsql';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'sqlsrv':
        Database::$dbType = 'sqlsrv';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'oci':
        Database::$dbType = 'oci';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'mongodb':
        Database::$dbType = 'mongodb';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'cassandra':
        Database::$dbType = 'cassandra';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'redis':
        Database::$dbType = 'redis';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbPort = $env['DBPORT'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'firebase':
        Database::$dbType = 'firebase';
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'couchdb':
        Database::$dbType = 'couchdb';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'dynamodb':
        Database::$dbType = 'dynamodb';
        Database::$dbHost = $env['DBHOST'] ?? null;
        Database::$dbName = $env['DBNAME'] ?? null;
        Database::$dbUser = $env['DBUSER'] ?? null;
        Database::$dbPass = $env['DBPASSWORD'] ?? null;
        $pdo = Database::connect(); // Connect after setting all properties
        break;
    case 'blank':
    case null:
    case '':
        // No database connection needed
        break;

    default:
        // Handle unsupported database type or no type specified
        // You might want to log an error or throw an exception here
        //handle_fatal_error("Unsupported or unspecified database type: " . ($env['DBTYPE'] ?? 'null'), getCurrentErrorFile(), getCurrentErrorLine());
        require_once(SysInfo);// start system
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');        
        break;
}

?>