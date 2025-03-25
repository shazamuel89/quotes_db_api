<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Quote.php';

    // Get input parameters
    $input = json_decode(file_get_contents("php://input"));     // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($input->quote) ||                                // If the quote, author_id, or category_id values weren't provided
        !isset($input->author_id) ||
        !isset($input->category_id)
    ) {
        echo json_encode([
            'message'   =>  'Missing Required Parameters'       // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified quote, author_id, and category_id parameters were provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $quote = new Quote($db);                                    // Instantiate a Quote object that has the connection to the Database object
    $quote->setQuote($input->quote);                            // Put quote value from request into Quote object (and sanitize it)
    $quote->setAuthor_id($input->author_id);                    // Put author_id value from request into Quote object (and sanitize it)
    $quote->setCategory_id($input->category_id);                // Put category_id value from request into Quote object (and sanitize it)
    
    try {
        // Execute request
        $result = $quote->create();                             // Create quote entry and get result
    } catch (PDOException $e) {                                 // If a database error occurred
        echo json_encode([
            'message'   =>  'A database error occurred: ' . $e  // Output the error message
        ]);
        exit();                                                 // And exit the script
    } catch (Exception $e) {                                    // If another error occurred (most likely meaning author_id or category_id not matching)
        echo json_encode([
            'message'   =>  $e                                  // Output the error message
        ]);
        exit();                                                 // And exit the script
    }
    
    // Fetch results
    $quoteArr = $result->fetch(PDO::FETCH_ASSOC);               // Get the newly created row from the PDOStatement object in the result
    
    // No need to verify results were fetched, because if create query didn't return a result, then create query must have failed which would have been caught earlier
    
    // Output results
    echo json_encode($quoteArr);                                // Output in json an array containing the quote's data
?>