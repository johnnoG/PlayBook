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

    // Method to check user credentials
    public function verifyUser($email, $password)
    {
        $query = "SELECT password FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                return true;
            }
        }
        return false;
    }

    // Method to insert data into a table
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

    // Method to close the connection 
    public function closeConnection()
    {
        $this->conn->close();
    }
}
/*
// Usage example:
$db = Database::getInstance();

// Verify user
if ($db->verifyUser("user@example.com", "user_password")) {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}

// Insert data
$data = ["fieldID" => "123456", "FieldType" => "soccer"];
if ($db->insertData("field", $data)) {
    echo "Data inserted successfully!";
} else {
    echo "Error inserting data.";
}

*/