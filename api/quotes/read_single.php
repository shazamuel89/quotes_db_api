<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Quote.php';

    define('USER_MESSAGE', 'Quote lookup failed.');                                 // This is a constant that defines what user readable message is output for errors

    if (!isset($_GET['id'])) {                                                      // If $_GET superglobal does not contain necessary id
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that ID was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $quote = new Quote($db);                                                        // Instantiate a Quote object that has the connection to the Database object
    $quote->setId($_GET['id']);                                                     // Set Quote object's id (and sanitize it)
    $resultArr = $quote->read_single();                                             // Get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $quoteArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                        // Fetch the result of read_single() as an associative array (or false if no rows)
    if ($quoteArr === false) {                                                      // If there were no rows found matching the id
        $errorTypeArr = $errorTypesData['quote not found'];                         // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that a row was found
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Quote found.',
        'data'      =>  $quoteArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the quote's data
?>