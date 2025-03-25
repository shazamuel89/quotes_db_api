<?php
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../functions/model.php';

    class Quote {
        // Database properties
        private $conn;
        private $table = 'quotes';

        // Attribute properties
        private $id;
        private $quote;
        private $author_id;
        private $category_id;

        // Store the connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // Getters
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

        // Setters
        public function setId($id) {
            $this->id = (int) $id;  // Also sanitizes input
        }
        public function setQuote($quote) {
            $this->quote = strip_tags(trim($quote));    // Also sanitizes input
        }
        public function setAuthor_id($author_id) {
            $this->author_id = (int) $author_id;    // Also sanitizes input
        }
        public function setCategory_id($category_id) {
            $this->category_id = (int) $category_id;    // Also sanitizes input
        }

        // Main database functions
        public function read() {
            // Initialize query
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
            
            // Check for filters and format query accordingly
            $filters = [];                                              // Create an array for any potential filters
            if (isset($this->author_id)) {                              // If author_id is specified
                $filters[] = 'author_id = :author_id';                  // Put author condition for WHERE clause into filters array
            }
            if (isset($this->category_id)) {                            // If category_id is specified
                $filters[] = 'category_id = :category_id';              // Put category condition for WHERE clause into filters array
            }
            if (count($filters) > 0) {                                  // If any conditions were put into filters array
                $query .= ' WHERE ' . implode(' AND ', $filters);       // Append WHERE clause onto query along with any potential filters
            }

            // Finish writing query
            $query .= '
                ORDER BY
                    a.author,
                    c.category;
            ';                                                          // Finish writing query
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);                       // Prepare statement
            
            // Bind values
            if (isset($this->author_id)) {                              // If author_id was specified
                $stmt->bindValue(':author_id', $this->author_id);       // Bind author_id value
            }
            if (isset($this->category_id)) {                            // If category_id was specified
                $stmt->bindValue(':category_id', $this->category_id);   // Bind category_id value
            }
            
            // Execute query
            return $stmt->execute();                                    // Execute query, returning result
        }
        public function read_single() {
            // Initialize query
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
            ';                                      // Select query for specific id using join with authors and categories table 
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);   // Prepare statement
            
            // Bind values
            $stmt->bindValue(':id', $this->id);     // Bind id value
            
            // Execute query
            return $stmt->execute();                // Execute query, returning result
        }
        public function create() {
            // Validate foreign keys
            if (!validateForeignKey($this->conn, 'authors', $this->author_id)) {        // If author matching author_id does not exist
                throw new Exception('author_id Not Found');                             // Throw custom exception to controller
            }                                                                           // Verified that author matching author_id exists
            if (!validateForeignKey($this->conn, 'categories', $this->category_id)) {   // If category matching category_id does not exist
                throw new Exception('category_id Not Found');                           // Throw custom exception to controller
            }                                                                           // Verified that category matching category_id exists
            
            // Initialize query
            $createQuery = '
                INSERT INTO
                    ' . $this->table . '
                    (quote, author_id, category_id)
                VALUES
                    (:quote, :author_id, :category_id)
                RETURNING
                    *;
            ';                                                                          // Basic insert query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($createQuery);                                 // Prepare statement
            
            // Bind values
            $stmt->bindValue(':quote', $this->quote);                                   // Bind quote value
            $stmt->bindValue(':author_id', $this->author_id);                           // Bind author_id value
            $stmt->bindValue(':category_id', $this->category_id);                       // Bind category_id value
            
            // Execute query
            return $stmt->execute();                                                    // Execute query, returning result
        }
        public function update() {
            // Validate foreign keys
            if (!validateForeignKey($this->conn, 'authors', $this->author_id)) {        // If author matching author_id does not exist
                throw new Exception('author_id Not Found');                             // Throw custom exception to controller
            }                                                                           // Verified that author matching author_id exists
            if (!validateForeignKey($this->conn, 'categories', $this->category_id)) {   // If category matching category_id does not exist
                throw new Exception('category_id Not Found');                           // Throw custom exception to controller
            }                                                                           // Verified that category matching category_id exists

            // Initialize query
            $query = '
                UPDATE
                    ' . $this->table . '
                SET
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id
                WHERE
                    id = :id
                RETURNING
                    *;
            ';                                                                          // Basic update query, uses returning so model can return affected rows back to controller
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);                                       // Prepare statement
            
            // Bind values
            $stmt->bindValue(':quote', $this->quote);                                   // Bind quote value
            $stmt->bindValue(':author_id', $this->author_id);                           // Bind author_id value
            $stmt->bindValue(':category_id', $this->category_id);                       // Bind category_id value
            $stmt->bindValue(':id', $this->id);                                         // Bind id value
            
            // Execute query
            return $stmt->execute();                                                    // Execute query, returning result
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
            return $stmt->execute();                // Execute query, returning result
        }
    }
?>