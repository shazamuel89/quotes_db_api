<?php
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../functions/model.php';

    class Author {
        // Database properties
        private $conn;
        private $table = 'authors';

        // Attribute properties
        private $id;
        private $author;

        // Store the connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters
        public function getId() {
            return $this->id;
        }
        public function getAuthor() {
            return $this->author;
        }

        // Setters
        public function setId($id) {
            $this->id = (int) $id;  // This also sanitizes input
        }
        public function setAuthor($author) {
            $this->author = strip_tags(trim($author));  // This also sanitizes input
        }

        // Main database functions
        public function read() {
            // Initialize query
            $query = '
                SELECT
                    id,
                    author
                FROM
                    ' . $this->table . '
                ORDER BY
                    author;
            ';                                      // Basic select query, ordered alphabetically
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);   // Prepare statement
            
            // Execute query
            return executeQuery($stmt);             // Execute query, returning result array
        }
        public function read_single() {
            // Initialize query
            $query = '
                SELECT
                    id,
                    author
                FROM
                    ' . $this->table . '
                WHERE
                    id = :id
                LIMIT 1;
            ';                                      // Basic select query for specific id
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);   // Prepare statement
            
            // Bind values
            $stmt->bindValue(':id', $this->id);     // Bind id value
            
            // Execute query
            return executeQuery($stmt);             // Execute query, returning result array
        }
        public function create() {
            // Initialize query
            $query = '
                INSERT INTO
                    ' . $this->table . '
                    (author)
                VALUES
                    (:author)
                RETURNING
                    *;
            ';                                          // Basic insert query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);       // Prepare statement
            
            // Bind values
            $stmt->bindValue(':author', $this->author); // Bind author value
            
            // Execute query
            return executeQuery($stmt);                 // Execute query, returning result array
        }
        public function update() {
            // Initialize query
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    author = :author
                WHERE
                    id = :id
                RETURNING
                    *;
            ';                                          // Basic update query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);       // Prepare statement
            
            // Bind values
            $stmt->bindValue(':author', $this->author); // Bind author value
            $stmt->bindValue(':id', $this->id);         // Bind id value
            
            // Execute query
            return executeQuery($stmt);                 // Execute query, returning result array
        }
        public function delete() {
            // Initialize query
            $query = '
                DELETE FROM
                    ' . $this->table . '
                WHERE
                    id = :id
                RETURNING
                    *;
            ';                                      // Basic delete query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);   // Prepare statement
            
            // Bind values
            $stmt->bindValue(':id', $this->id);     // Bind id value
            
            // Execute query
            return executeQuery($stmt);             // Execute query, returning result array
        }
    }
?>