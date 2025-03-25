<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Category.php';

    // Define constants
    define('USER_MESSAGE', 'Category creation failed.');                            // This is a constant that defines what user readable message is output for errors

    // Get input parameters
    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($data->category)) {                                                  // If the category value wasn't provided
        $errorTypeArr = $errorTypesData['missing category parameter'];              // Get individual error type's data
        echo getError($errorTypeArr, 'Missing Required Parameters');                                 // Output error message
        exit();                                                                     // Exit script
    }                                                                               // Verified category parameter was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $category = new Category($db);                                                  // Instantiate a Category object that has the connection to the Database object
    $category->setCategory($data->category);                                        // Put category value from request into Category object (and sanitize it)
    
    // Execute request
    $resultArr = $category->create();                                               // Create category entry and get result array
    
    // Verify success
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        echo getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? '');    // Output the error
        exit();                                                                     // Exit the script
    }                                                                               // Verified the query was a success
    
    // Fetch results
    $categoryArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                     // Get the newly created row from the PDOStatement object in the result array
    
    // No need to verify results were fetched, because if create query didn't return a result, then create query must have failed which would have been caught earlier

    // Signal success and output results
    http_response_code(201);                                                        // Set the http status code to 201 for successful POST
    echo json_encode($categoryArr);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the category's data
?>