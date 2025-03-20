<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    require_once '../../functions/controller.php';
    include_once '../../models/Author.php';

    define('USER_MESSAGE', 'Author read failed.');                                  // This is a constant that defines what user readable message is output for errors

    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    $resultArr = $author->read();                                                   // Get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $authorsArr = $resultArr['data']->fetchAll(PDO::FETCH_ASSOC);                   // Get array of rows, each row as an associative array with key/value being the column/value
    if (count($authorsArr) === 0) {                                                 // If there were no results from read query
        $errorTypeArr = $errorTypesData['author not found'];                        // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that rows were found
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Authors found.',
        'data'      =>  $authorsArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the array of rows
?>