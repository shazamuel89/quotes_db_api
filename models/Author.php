<?php
    require_once '../functions/database.php';   // Include this for the executeQuery($stmt) function
    class Author {
        private $conn;
        private $table = 'authors';

        // Properties
        private $id;
        private $author;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters and setters
        public function getId() {
            return $this->id;
        }
        public function getAuthor() {
            return $this->author;
        }
        public function setId($id) {
            $this->id = $id;
        }
        public function setAuthor($author) {
            $this->author = $author;
        }

        public function read() {
            $query = '
                SELECT
                    id,
                    author
                FROM
                    ' . $this->table . '
                ORDER BY
                    author;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $stmt->execute();                       // Execute query
            return $stmt;
        }
        public function read_single() {
            $query = '
                SELECT
                    id,
                    author
                FROM
                    ' . $this->table . '
                WHERE
                    id = :id
                LIMIT 1;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $this->id = (int) $this->id;            // Clean data
            $stmt->bindParam(':id', $this->id);     // Bind data
            $stmt = executeQuery($stmt);            // Execute and get new $stmt (or assign false to $stmt if query fails)
            if ($stmt === false) {                  // If query failed
                return false;                       // Return false
            }                                       // Otherwise, if query was successful (doesn't mean it necessarily found results)
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch the result of the query as an associative array            
            if ($row) {                             // If there was a result found
                $this->author = $row['author'];     // Assign author value of row to object property
                return true;                        // Return true to indicate success and result found
            } else {                                // If no result was found
                return false;                       // Return false to indicate no result found
            }
        }
        public function create() {
            $query = '
                INSERT INTO
                    ' . $this->table . '
                SET
                    author = :author;
            ';
            $stmt = $this->conn->prepare($query);                           // Prepare statement
            $this->author = htmlspecialchars(strip_tags($this->author));    // Clean data
            $stmt->bindParam(':author', $this->author);                     // Bind data
            return executeQuery($stmt) !== false;                           // Execute query, if it works it returns $stmt, so return true, otherwise prints error and return false
        }
        public function update() {
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    author = :author
                WHERE
                    id = :id;
            ';
            $stmt = $this->conn->prepare($query);                           // Prepare statement
            $this->author = htmlspecialchars(strip_tags($this->author));    // Clean data
            $this->id = (int) $this->id;
            $stmt->bindParam(':author', $this->author);                     // Bind data
            $stmt->bindParam(':id', $this->id);
            return executeQuery($stmt) !== false;                           // Execute query, if it works it returns $stmt, so return true, otherwise prints error and return false
        }
        public function delete() {
            $query = '
                DELETE FROM
                    ' . $this->table . '
                WHERE
                    id = :id;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $this->id = (int) $this->id;            // Clean data
            $stmt->bindParam(':id', $this->id);     // Bind data
            return executeQuery($stmt) !== false;   // Execute query, if it works it returns $stmt, so return true, otherwise prints error and return false
        }
    }
?>