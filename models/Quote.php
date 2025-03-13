<?php
    require_once '../functions/database.php';   // Include this for the executeQuery($stmt) function
    class Quote {
        private $conn;
        private $table = 'quotes';

        // Properties
        private $id;
        private $quote;
        private $author_id;
        private $category_id;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters and setters
        public function getId() {
            return $this->id;
        }
        public function getQuote() {
            return $this->quote;
        }
        public function getAuthor_id() {
            return $this->author_id;
        }
        public function getCategory_id() {
            return $this->category_id;
        }
        public function setId($id) {
            $this->id = $id;
        }
        public function setQuote($quote) {
            $this->quote = $quote;
        }
        public function setAuthor_id($author_id) {
            $this->author_id = $author_id;
        }
        public function setCategory_id($category_id) {
            $this->category_id = $category_id;
        }

        public function read() {
            $query = '
                SELECT
                    id,
                    quote,
                    author_id,
                    category_id
                FROM
                    ' . $this->table . '
                ORDER BY
                    category_id;
            ';
            $stmt = $this->conn->prepare($query);   // Prepare statement
            $stmt->execute();                       // Execute query
            return $stmt;
        }
        public function read_single() {
            $query = '
                SELECT
                    id,
                    quote,
                    author_id,
                    category_id
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
                $this->quote = $row['quote'];       // Assign values of row to object properties
                $this->author_id = $row['author_id'];
                $this->category_id = $row['category_id'];
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
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id;
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