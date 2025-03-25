<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Quote.php';

    // Define constants
    define('USER_MESSAGE', 'Quote read failed.');                                   // This is a constant that defines what user readable message is output for errors

    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $quote = new Quote($db);                                                        // Instantiate a Quote object that has the connection to the Database object
    
    // Initialize filters if they were applied
    if (isset($_GET['author_id'])) {                                                // If $_GET superglobal contains optional author_id filter
        $quote->setAuthor_id($_GET['author_id']);                                   // Then set the Quote object's author_id property to the author_id to filter by
    }                                                                               // Verified author_id filter is accounted for
    if (isset($_GET['category_id'])) {                                              // If $_GET superglobal contains optional category_id filter
        $quote->setCategory_id($_GET['category_id']);                               // Then set the Quote object's category_id property to the category_id to filter by
    }                                                                               // Verified category_id filter is accounted for
    
    // Execute request
    $resultArr = $quote->read();                                                    // Get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $quotesArr = $resultArr['data']->fetchAll(PDO::FETCH_ASSOC);                    // Get array of rows, each row as an associative array with key/value being the column/value
    
    // Verify results were fetched
    if (count($quotesArr) === 0) {                                                  // If there were no results from read query
        $errorTypeArr = $errorTypesData['quote not found'];                         // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that rows were found
    
    // Signal success and output results
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode($quotesArr);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the array of rows
?>