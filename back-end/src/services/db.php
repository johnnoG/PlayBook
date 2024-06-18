<?php
class Database
{
    private $servername = "sql305.byethost9.com";
    private $username = "b9_36704350";
    private $password = "@ToharyY0123";
    private $dbname = "b9_36704350_PlayBook";
    private $conn;

    // Singleton instance
    private static $instance = null;

    // Private constructor to prevent multiple instances
    private function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get the Singleton instance
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Method to get the connection
    public function getConnection()
    {
        return $this->conn;
    }

    // Modified insertData function
    public function insertData($table, $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        $types = str_repeat('s', count($data)); // Assuming all fields are strings
        $stmt->bind_param($types, ...array_values($data));

        return $stmt->execute();
    }

    // Method to close the connection (optional, for cleanup)
    public function closeConnection()
    {
        $this->conn->close();
    }
}
