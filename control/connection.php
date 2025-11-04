<?php

class Database
{
    private static $pdo = null;
    public static $dbName = null;
    public static $dbPath = 'data/';
    public static $dbHost = null;
    public static $dbUser = null;
    public static $dbPass = null;
    public static $dbType = null;
    public static $dbPort = null;
    public static $dbCharset = 'utf8';
    public static $dbCollation = 'utf8_general_ci';
    public static $dbExtension = '.db';

    /**
     * Establishes a static PDO connection to a database.
     *
     * @return PDO|string A PDO instance on successful connection, or an error message string.
     */
    public static function connect(): PDO|string
    {
        // Check if a connection already exists.
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dbType = self::$dbType;

        switch ($dbType) {
            case 'sqlite':
                return self::connectToSQLite();
            case 'mysql':
                return self::connectToMySQL();
            case 'access':
                return self::connectToMsAccess();
            default:
                // Return an error message if the database type is invalid.
                handle_fatal_error("Error: Invalid database type specified: '$dbType'.", getCurrentErrorFile(), getCurrentErrorLine());
                return "Error: Invalid database type specified: '$dbType'.";
        }
    }

     /**
     * Establishes a static PDO connection to a database.
     *
     * @return PDO|string A PDO instance on successful connection, or an error message string.
     */
    public static function connection(): PDO|string
    {
        // Check if a connection already exists.
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dbType = self::$dbType;

        switch ($dbType) {
            case 'sqlite':
                return self::connectToSQLite();
            case 'mysql':
                return self::connectToMySQL();
            case 'access':
                return self::connectToMsAccess();
            default:
                // Return an error message if the database type is invalid.
                handle_fatal_error("Error: Invalid database type specified: '$dbType'.", getCurrentErrorFile(), getCurrentErrorLine());
                return "Error: Invalid database type specified: '$dbType'.";
        }
    }

    /**
     * Establishes a static PDO connection to a SQLite database.
     *
     * @return PDO|string A PDO instance on successful connection, or an error message string.
     */
    private static function connectToSQLite(): PDO|string
    {
        $dbPath = self::$dbPath;
        $dbName = self::$dbName;
        $dbExtension = self::$dbExtension;

        // Ensure the database directory exists.
        if (!is_dir($dbPath)) {
            if (!mkdir($dbPath, 0755, true)) {
                handle_fatal_error("Error: Could not create database directory '$dbPath'.", getCurrentErrorFile(), getCurrentErrorLine());
                return "Error: Could not create database directory '$dbPath'.";
            }
        }

        // Construct the full database file path.
        $dbFile = $dbPath . $dbName . $dbExtension;//'.db';

        try {
            // Create a new PDO instance for SQLite.
            self::$pdo = new PDO("sqlite:$dbFile");
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: Database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: Database connection failed: " . $e->getMessage();
        }
    }

    /**
     * Establishes a static PDO connection to a MySQL database.
     *
     * @return PDO|string A PDO instance on successful connection, or an error message string.
     */
    private static function connectToMySQL(): PDO|string
    {
        $dbHost = self::$dbHost;
        $dbName = self::$dbName;
        $dbUser = self::$dbUser;
        $dbPass = self::$dbPass;
        $dbPort = self::$dbPort;
        $dbCharset = self::$dbCharset;
        $dbCollation = self::$dbCollation;

        try {
            // Create a new PDO instance for MySQL.
            self::$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset;collation=$dbCollation;port=$dbPort", $dbUser, $dbPass);
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: MySQL database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: MySQL database connection failed: " . $e->getMessage();
        }
    }

    /**
     * Establishes a static PDO connection to a MS Access database.
     *
     * @return PDO|string A PDO instance on successful connection, or an error message string.
     */
    private static function connectToMsAccess(): PDO|string
    {
        $dbPath = self::$dbPath;
        $dbName = self::$dbName;

        // Ensure the database directory exists.
        if (!is_dir($dbPath)) {
            if (!mkdir($dbPath, 0755, true)) {
                return "Error: Could not create database directory '$dbPath'.";
            }
        }

        // Construct the full database file path.
        $dbFile = $dbPath . $dbName . '.mdb';

        try {
            // Create a new PDO instance for MS Access.
            self::$pdo = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbFile;");
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: MS Access database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: MS Access database connection failed: " . $e->getMessage();
        }
    }

    private static function connectToPostgreSQL(): PDO|string
    {
        $dbHost = self::$dbHost;
        $dbName = self::$dbName;
        $dbUser = self::$dbUser;
        $dbPass = self::$dbPass;

        try {
            // Create a new PDO instance for PostgreSQL.
            self::$pdo = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: PostgreSQL database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: PostgreSQL database connection failed: " . $e->getMessage();
        }
    }

    private static function connectToSQLServer(): PDO|string
    {
        $dbHost = self::$dbHost;
        $dbName = self::$dbName;
        $dbUser = self::$dbUser;
        $dbPass = self::$dbPass;

        try {
            // Create a new PDO instance for SQL Server.
            self::$pdo = new PDO("sqlsrv:Server=$dbHost;Database=$dbName", $dbUser, $dbPass);
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: SQL Server database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: SQL Server database connection failed: " . $e->getMessage();
        }
    }

    private static function connectToOracle(): PDO|string
    {
        $dbHost = self::$dbHost;
        $dbName = self::$dbName;
        $dbUser = self::$dbUser;
        $dbPass = self::$dbPass;

        try {
            // Create a new PDO instance for Oracle.
            self::$pdo = new PDO("oracle:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            // Set PDO error mode to exception.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array.
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$pdo;
        } catch (PDOException $e) {
            // Return an error message if the connection fails.
            handle_fatal_error("Error: Oracle database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return "Error: Oracle database connection failed: " . $e->getMessage();
        }
    }

    private static function connectToMongoDB(): PDO|string
    {
        // MongoDB connection logic would go here.
        // Note: MongoDB does not use PDO, so this is just a placeholder.
        return "Error: MongoDB connection not implemented.";
    }

    private static function connectToRedis(): PDO|string
    {
        // Redis connection logic would go here.
        // Note: Redis does not use PDO, so this is just a placeholder.
        return "Error: Redis connection not implemented.";
    }

    private static function connectToFirebase(): PDO|string
    {
        // Firebase connection logic would go here.
        // Note: Firebase does not use PDO, so this is just a placeholder.
        return "Error: Firebase connection not implemented.";
    }   

    private static function connectToCouchDB(): PDO|string
    {
        // CouchDB connection logic would go here.
        // Note: CouchDB does not use PDO, so this is just a placeholder.
        return "Error: CouchDB connection not implemented.";
    }

    private static function connectToDynamoDB(): PDO|string
    {
        // DynamoDB connection logic would go here.
        // Note: DynamoDB does not use PDO, so this is just a placeholder.
        return "Error: DynamoDB connection not implemented.";
    }

    private static function connectToCassandra(): PDO|string
    {
        // Cassandra connection logic would go here.
        // Note: Cassandra does not use PDO, so this is just a placeholder.
        return "Error: Cassandra connection not implemented.";
    }   
    
    // private static function connectToSQLite(): PDO|string
    // {
    //     $dbPath = self::$dbPath;
    //     $dbName = self::$dbName;
    //     $dbExtension = self::$dbExtension;

    //     // Ensure the database directory exists.
    //     if (!is_dir($dbPath)) {
    //         if (!mkdir($dbPath, 0755, true)) {
    //             handle_fatal_error("Error: Could not create database directory '$dbPath'.", getCurrentErrorFile(), getCurrentErrorLine());
    //             return "Error: Could not create database directory '$dbPath'.";
    //         }
    //     }

    //     try {
    //         // Create a new PDO instance for SQLite.
    //         self::$pdo = new PDO("sqlite:$dbPath/$dbName.$dbExtension");
    //         // Set PDO error mode to exception.
    //         self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //         // Set default fetch mode to associative array.
    //         self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //         return self::$pdo;
    //     } catch (PDOException $e) {
    //         // Return an error message if the connection fails.
    //         handle_fatal_error("Error: SQLite database connection failed: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
    //         return "Error: SQLite database connection failed: " . $e->getMessage();
    //     }
    // }

    /**
     * Closes the static PDO connection.
     *
     * This method is optional, as PHP will automatically close the connection
     * at the end of the script's execution. However, you can use it to
     * explicitly close the connection if needed.
     */
    public static function closeConnection(): void
    {
        self::$pdo = null;
    }
    
    // Database::disconnect();
    /**
     * Closes the static PDO connection.
     *
     * This method is optional, as PHP will automatically close the connection
     * at the end of the script's execution. However, you can use it to
     * explicitly close the connection if needed.
     */
    public static function disconnect(): void
    {
        self::$pdo = null;
    }
}

// Example usage (you can uncomment this to test):
// Database::$dbType = 'sqlite';
// Database::$dbName = 'grades';
// $pdo = Database::connect();
// if ($pdo instanceof PDO) {
//     echo "Database connection successful!\n";
//     // Perform database operations here...
//     // Example:
//     // $stmt = $pdo->query("SELECT SQLITE_VERSION()");
//     // $version = $stmt->fetchColumn();
//     // echo "SQLite Version: " . $version . "\n";
//     Database::closeConnection(); // Optional: Close the connection explicitly.
// } else {
//     echo $pdo; // Output the error message.
// }

// Example usage (you can uncomment this to test):
// Database::$dbType = 'mysql';
// Database::$dbHost = 'localhost';
// Database::$dbName = 'your_mysql_db';
// Database::$dbUser = 'your_mysql_user';
// Database::$dbPass = 'your_mysql_password';
// $pdo = Database::connect();
// if ($pdo instanceof PDO) {
//     echo "Database connection successful!\n";
//     // Perform database operations here...
//     // Example:
//     // $stmt = $pdo->query("SELECT VERSION()");
//     // $version = $stmt->fetchColumn();
//     // echo "MySQL Version: " . $version . "\n";
//     Database::closeConnection(); // Optional: Close the connection explicitly.
// } else {
//     echo $pdo; // Output the error message.
// }

// Example usage (you can uncomment this to test):
// Database::$dbType = 'access';
// Database::$dbName = 'your_access_db';
// $pdo = Database::connect();
// if ($pdo instanceof PDO) {
//     echo "Database connection successful!\n";
//     // Perform database operations here...
//     Database::closeConnection(); // Optional: Close the connection explicitly.
// } else {
//     echo $pdo; // Output the error message.
// }
?>
