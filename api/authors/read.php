<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Author.php';

    // Define constants
    define('USER_MESSAGE', 'Author read failed.');                                  // This is a constant that defines what user readable message is output for errors

    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    
    // Execute request
    $resultArr = $author->read();                                                   // Get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? '');    // Output the error
        exit();                                                                     // Exit the script
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $authorsArr = $resultArr['data']->fetchAll(PDO::FETCH_ASSOC);                   // Get array of rows, each row as an associative array with key/value being the column/value
    
    // Verify results were fetched
    if (count($authorsArr) === 0) {                                                 // If there were no results from read query
        $errorTypeArr = $errorTypesData['author not found'];                        // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE);                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified that rows were found
    
    // Signal success and output results
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode($authorsArr);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the array of rows
?>