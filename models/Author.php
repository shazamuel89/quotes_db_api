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
        public function setId($id) {    // This also sanitizes input
            $this->id = (int) $id;
        }
        public function setAuthor($author) {    // This also sanitizes input
            $this->author = strip_tags(trim($author));
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
            return executeQuery($stmt);             // Execute query
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
            $stmt->bindValue(':id', $this->id);     // Bind data
            return executeQuery($stmt);             // Execute and return result, or return false if query fails
        }
        public function create() {
            $query = '
                INSERT INTO
                    ' . $this->table . '
                SET
                    author = :author;
            ';
            $stmt = $this->conn->prepare($query);                           // Prepare statement
            $stmt->bindValue(':author', $this->author);                     // Bind data
            return executeQuery($stmt);                                     // Execute query, if it works return $stmt, otherwise log error and return false
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
            $stmt->bindValue(':author', $this->author);                     // Bind data
            $stmt->bindValue(':id', $this->id);                             // Bind data
            return executeQuery($stmt);                                     // Execute query, if it works return $stmt, otherwise log error and return false
        }
        public function delete() {
            $query = '
                DELETE FROM
                    ' . $this->table . '
                WHERE
                    id = :id;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $stmt->bindValue(':id', $this->id);     // Bind data
            return executeQuery($stmt);             // Execute query, if it works return $stmt, otherwise log error and return false
        }
    }
?>