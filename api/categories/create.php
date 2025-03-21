<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers:
            Access-Control-Allow-Headers,
            Content-Type,
            Access-Control-Allow-Methods,
            Authorization,
            X-Requested-With');
    
    require_once '../../config/Database.php';
    require_once '../../functions/controller.php';
    require_once '../../models/Category.php';

    define('USER_MESSAGE', 'Category creation failed.');                            // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->category)) {                                                  // If the category value wasn't provided
        $errorTypeArr = $errorTypesData['missing category parameter'];              // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified category parameter was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $category = new Category($db);                                                  // Instantiate a Category object that has the connection to the Database object
    $category->setCategory($data->category);                                        // Put category value from request into Category object (and sanitize it)
    $resultArr = $category->create();                                               // Create category entry and get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $categoryArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                     // Get the newly created row from the PDOStatement object in the result array
    http_response_code(201);                                                        // Set the http status code to 201 for successful POST
    echo json_encode([
        'message'   =>  'Category created.',
        'data'      =>  $categoryArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the category's data
?>