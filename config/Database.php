<?php
    // This is for online connection
    class Database {
        
        private $host;
        private $dbname;
        private $username;
        private $password;
        private $conn;

        public function __construct() {
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
        }

        public function connect() {
            if ($this->conn) {
                // connection already exists, return it
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->host};dbname={$this->dbname};";
                try {
                    $this->conn = new PDO($dsn, $this->username, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                } catch (PDOException $e) {
                    echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }

/*
// This is for local connection
class Database {
    // Properties
    private $host = 'localhost';
    private $port = '5432';
    private $db_name = 'postgres';
    private $username = 'postgres';
    private $password = 'postgres';
    private $conn;

    public function connect() {
        $this->conn = null;
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // echo for tutorial, but log the error for production
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
*/
?>