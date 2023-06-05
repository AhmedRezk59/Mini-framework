<?php

namespace Core\Database;

use Core\File\File;
use PDO;

class Database
{
    /**
     * Database Instance
     *
     * @var [type]
     */
    protected static $instance;

    /**
     * Database Connection
     *
     * @var [type]
     */
    protected static $connection;

    /**
     * The SQL Query
     *
     * @var string
     */
    private static string $query;
    
    /**
     * The SQL query bindings
     *
     * @var array
     */
    private static array $bindings;

    /**
     * Private Constructor
     */
    private function __construct()
    {
    }

    /**
     * Connect to the database
     *
     * @return void
     */
    private static function connect() :void
    {
        if (!static::$connection) {
            $data = File::require_once('config' . ds() . 'database.php');
            extract($data);
            $dsn = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'set NAMES ' . $DB_CHARSET . ' COLLATE ' . $DB_COLLATION
            ];

            try {
                static::$connection = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD, $options);
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * Get an instance of the Database class
     *
     * @return Database
     */
    private static function instance() :Database
    {
        static::connect();
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Function to set our query and its bindings 
     *
     * @param string $query
     * @param array $bindings
     * @return static
     */
    public static function query (string $query , array $bindings = []) : static 
    {
        static::instance();        
        static::$query = $query;
        static::$bindings = $bindings;
        return static::$instance;
    }

    /**
     * Execute the query
     *
     * @return array
     */
    public static function execute () :array
    {
        $data = static::$connection->prepare(static::$query);
        $data->execute(static::$bindings);
        $result = $data->fetchAll();
        return $result;
    }
}
