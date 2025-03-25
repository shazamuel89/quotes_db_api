<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Author.php';

    // Define constants
    define('USER_MESSAGE', 'Author lookup failed.');                                // This is a constant that defines what user readable message is output for errors

    // Verify input parameters were provided
    if (!isset($_GET['id'])) {                                                      // If $_GET superglobal does not contain necessary id
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that ID was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    $author->setId($_GET['id']);                                                    // Set Author object's id (and sanitize it)
    
    // Execute request
    $resultArr = $author->read_single();                                            // Get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                       // Fetch the result of read_single() as an associative array (or false if no rows)
    
    // Verify results were fetched
    if ($authorArr === false) {                                                     // If there were no rows found matching the id
        $errorTypeArr = $errorTypesData['author not found'];                        // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that a row was found
    
    // Signal success and output results
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode($authorArr);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>