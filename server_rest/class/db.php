<?php

class DB {
    private $server = "localhost";
    private $user = "root";
    private $password = "";
    private $db_name = "school_resources";
    private $connection = null;

    public function __construct() {
        try {
            $this->connection = new PDO("mysql:host={$this->server};dbname={$this->db_name}", $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "<script>alert('Connection to database failed: " . $e->getMessage() . "');</script>";
            die();
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Usage:
// require 'DB.php';
// $db = new DB();
// $connection = $db->getConnection();

?>
