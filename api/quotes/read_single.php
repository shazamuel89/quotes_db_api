<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Quote.php';

    // Define constants
    define('USER_MESSAGE', 'Quote lookup failed.');                                 // This is a constant that defines what user readable message is output for errors

    // Verify input parameters were provided
    if (!isset($_GET['id'])) {                                                      // If $_GET superglobal does not contain necessary id
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE);                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified that ID was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $quote = new Quote($db);                                                        // Instantiate a Quote object that has the connection to the Database object
    $quote->setId($_GET['id']);                                                     // Set Quote object's id (and sanitize it)
    
    // Execute request
    $resultArr = $quote->read_single();                                             // Get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? '');    // Output the error
        exit();                                                                     // Exit the script
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $quoteArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                        // Fetch the result of read_single() as an associative array (or false if no rows)
    
    // Verify results were fetched
    if ($quoteArr === false) {                                                      // If there were no rows found matching the id
        $errorTypeArr = $errorTypesData['quote not found'];                         // Get individual error type's data
        echo getError($errorTypeArr, 'No Quotes Found');                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified that a row was found
    
    // Signal success and output results
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode($quoteArr);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the quote's data
?>