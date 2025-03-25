<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Author.php';

    // Define constants
    define('USER_MESSAGE', 'Author deletion failed.');                              // This is a constant that defines what user readable message is output for errors

    // Get input parameters
    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($data->id)) {                                                        // If the id value wasn't provided
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE);                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified id parameter was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    $author->setId($data->id);                                                      // Put id value from request into Author object (and sanitize it)
    
    // Execute request
    $resultArr = $author->delete();                                                 // Delete author entry and get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? '');    // Output the error
        exit();                                                                     // Exit the script
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                       // Get the deleted row from the PDOStatement object in the result array
    
    // Verify results were fetched
    if ($authorArr === false) {                                                     // If query fetch returned false (meaning the id input did not match an author's id)
        $errorTypeArr = $errorTypesData['author not found'];                        // Get individual error type's data
        echo getError($errorTypeArr, 'No Authors Found');                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified that an author was found and deleted
    
    // Signal success and output results
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'id'    =>  $authorArr['id']
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>