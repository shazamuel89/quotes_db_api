<?php
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../functions/model.php';

    class Category {
        // Database properties
        private $conn;
        private $table = 'categories';

        // Attribute properties
        private $id;
        private $category;

        // Store the connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters
        public function getId() {
            return $this->id;
        }
        public function getCategory() {
            return $this->category;
        }

        // Setters
        public function setId($id) {
            $this->id = (int) $id;  // This also sanitizes input
        }
        public function setCategory($category) {
            $this->category = strip_tags(trim($category));  // This also sanitizes input
        }

        // Main database functions
        public function read() {
            // Initialize query
            $query = '
                SELECT
                    id,
                    category
                FROM
                    ' . $this->table . '
                ORDER BY
                    category;
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
                    category
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
                    (category)
                VALUES
                    (:category)
                RETURNING
                    *;
            ';                                              // Basic insert query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);           // Prepare statement
            
            // Bind values
            $stmt->bindValue(':category', $this->category); // Bind data
            
            // Execute query
            return executeQuery($stmt);                     // Execute query, returning result array
        }
        public function update() {
            // Initialize query
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id
                RETURNING
                    *;
            ';                                              // Basic update query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);           // Prepare statement
            
            // Bind values
            $stmt->bindValue(':category', $this->category); // Bind category value
            $stmt->bindValue(':id', $this->id);             // Bind id value
            
            // Execute query
            return executeQuery($stmt);                     // Execute query, returning result array
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