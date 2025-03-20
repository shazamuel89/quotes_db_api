<?php
    require_once '../functions/model.php';
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
            return executeQuery($stmt);             // Execute query, returning result array
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
            $stmt->bindValue(':id', $this->id);     // Bind id value
            return executeQuery($stmt);             // Execute query, returning result array
        }
        public function create() {
            $query = '
                INSERT INTO
                    ' . $this->table . '
                SET
                    author = :author
                RETURNING
                    *;
            ';
            $stmt = $this->conn->prepare($query);       // Prepare statement
            $stmt->bindValue(':author', $this->author); // Bind author value
            return executeQuery($stmt);                 // Execute query, returning result array
        }
        public function update() {
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    author = :author
                WHERE
                    id = :id
                RETURNING
                    *;
            ';
            $stmt = $this->conn->prepare($query);       // Prepare statement
            $stmt->bindValue(':author', $this->author); // Bind author value
            $stmt->bindValue(':id', $this->id);         // Bind id value
            return executeQuery($stmt);                 // Execute query, returning result array
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