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
        public function setId($id) {    // This also sanitizes input
            $this->id = (int) $id;
        }
        public function setCategory($category) {    // This also sanitizes input
            $this->category = strip_tags(trim($category));
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
            return executeQuery($stmt);             // Execute query, returning result array
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
            $stmt->bindValue(':id', $this->id);     // Bind id value
            return executeQuery($stmt);             // Execute query, returning result array
        }
        public function create() {
            $query = '
                INSERT INTO
                    ' . $this->table . '
                SET
                    category = :category
                RETURNING
                    *;
            ';
            $stmt = $this->conn->prepare($query);           // Prepare statement
            $stmt->bindValue(':category', $this->category); // Bind data
            return executeQuery($stmt);                     // Execute query, returning result array
        }
        public function update() {
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id
                RETURNING
                    *;
            ';
            $stmt = $this->conn->prepare($query);           // Prepare statement
            $stmt->bindValue(':category', $this->category); // Bind category value
            $stmt->bindValue(':id', $this->id);             // Bind id value
            return executeQuery($stmt);                     // Execute query, returning result array
        }
        public function delete() {
            $query = '
                DELETE FROM
                    ' . $this->table . '
                WHERE
                    id = :id
                RETURNING
                    *;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $stmt->bindValue(':id', $this->id);     // Bind id value
            return executeQuery($stmt);             // Execute query, returning result array
        }
    }
?>