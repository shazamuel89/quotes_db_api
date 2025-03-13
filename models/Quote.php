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
        public function setId($id) {    // Also sanitizes input
            $this->id = (int) $id;
        }
        public function setQuote($quote) {  // Also sanitizes input
            $this->quote = strip_tags(trim($quote));
        }
        public function setAuthor_id($author_id) {  // Also sanitizes input
            $this->author_id = (int) $author_id;
        }
        public function setCategory_id($category_id) {  // Also sanitizes input
            $this->category_id = (int) $category_id;
        }

        public function read() {
            $query = '
                SELECT
                    q.id AS id,
                    quote,
                    a.author AS author,
                    c.category AS category
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a
                    ON
                    q.author_id = a.id
                LEFT JOIN
                    categories c
                    ON
                    q.category_id = c.id
            ';                                                          // Not finished with query yet, need to check if author and/or category is specified
            $filters = [];                                              // Create an array for any potential filters
            if (isset($this->author_id)) {                              // If author_id is specified
                $filters[] = 'author_id = :author_id';                  // Put author condition for WHERE clause into filters array
            }
            if (isset($this->category_id)) {                            // If category_id is specified
                $filters[] = 'category_id = :category_id';              // Put category condition for WHERE clause into filters array
            }
            if (count($filters) > 0) {                                  // If any conditions were put into filters array
                $query .= ' WHERE ' . implode(' AND ', $filters);    // Append WHERE clause onto query along with any potential filters
            }
            $query .= '
                ORDER BY
                    a.author,
                    c.category;
            ';                                                          // Then finish off query
            $stmt = $this->conn->prepare($query);                       // Prepare statement
            if (isset($this->author_id)) {                              // If author_id was specified
                $stmt->bindValue(':author_id', $this->author_id);       // Bind data
            }
            if (isset($this->category_id)) {                            // If category_id was specified
                $stmt->bindValue(':category_id', $this->category_id);   // Bind data
            }
            return executeQuery($stmt);                                 // Execute and return result so api can handle with flexibility
        }
        public function read_single() {
            $query = '
                SELECT
                    q.id AS id,
                    quote,
                    a.author AS author,
                    c.category AS category
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a
                    ON
                    q.author_id = a.id
                LEFT JOIN
                    categories c
                    ON
                    q.category_id = c.id
                WHERE
                    q.id = :id
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
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id;
            ';
            $stmt = $this->conn->prepare($query);                   // Prepare statement
            $stmt->bindValue(':quote', $this->quote);               // Bind data
            $stmt->bindValue(':author_id', $this->author_id);       // Bind data
            $stmt->bindValue(':category_id', $this->category_id);   // Bind data
            return executeQuery($stmt);                             // Execute query, if it works return $stmt, otherwise log error and return false
        }
        public function update() {
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id
                WHERE
                    id = :id;
            ';
            $stmt = $this->conn->prepare($query);                   // Prepare statement
            $stmt->bindValue(':quote', $this->quote);               // Bind data
            $stmt->bindValue(':author_id', $this->author_id);       // Bind data
            $stmt->bindValue(':category_id', $this->category_id);   // Bind data
            $stmt->bindValue(':id', $this->id);                     // Bind data
            return executeQuery($stmt);                             // Execute query, if it works return $stmt, otherwise log error and return false
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