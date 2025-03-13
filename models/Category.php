<?php
    require_once '../functions/database.php';   // Include this for the executeQuery($stmt) function
    class Category {
        private $conn;
        private $table = 'categories';

        // Properties
        private $id;
        private $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters and setters
        public function getId() {
            return $this->id;
        }
        public function getCategory() {
            return $this->category;
        }
        public function setId($id) {
            $this->id = $id;
        }
        public function setCategory($category) {
            $this->category = $category;
        }

        public function read() {
            $query = '
                SELECT
                    id,
                    category
                FROM
                    ' . $this->table . '
                ORDER BY
                    category;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $stmt->execute();                       // Execute query
            return $stmt;
        }
        public function read_single() {
            $query = '
                SELECT
                    id,
                    category
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
                $this->category = $row['category']; // Assign category value of row to object property
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
                    category = :category;
            ';
            $stmt = $this->conn->prepare($query);                               // Prepare statement
            $this->category = htmlspecialchars(strip_tags($this->category));    // Clean data
            $stmt->bindParam(':category', $this->category);                     // Bind data
            return executeQuery($stmt) !== false;                               // Execute query, if it works it returns $stmt, so return true, otherwise prints error and return false
        }
        public function update() {
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id;
            ';
            $stmt = $this->conn->prepare($query);                               // Prepare statement
            $this->category = htmlspecialchars(strip_tags($this->category));    // Clean data
            $this->id = (int) $this->id;
            $stmt->bindParam(':category', $this->category);                     // Bind data
            $stmt->bindParam(':id', $this->id);
            return executeQuery($stmt) !== false;                               // Execute query, if it works it returns $stmt, so return true, otherwise prints error and return false
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