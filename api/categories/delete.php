<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers:
            Access-Control-Allow-Headers,
            Content-Type,
            Access-Control-Allow-Methods,
            Authorization,
            X-Requested-With');
    
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Category.php';

    define('USER_MESSAGE', 'Category deletion failed.');                            // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                                        // If the id value wasn't provided
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified id parameter was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $category = new Category($db);                                                  // Instantiate a Category object that has the connection to the Database object
    $category->setId($data->id);                                                    // Put id value from request into Category object (and sanitize it)
    $resultArr = $category->delete();                                               // Delete category entry and get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $categoryArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                     // Get the deleted row from the PDOStatement object in the result array
    if ($categoryArr === false) {                                                   // If query fetch returned false (meaning the id input did not match a category's id)
        $errorTypeArr = $errorTypesData['category not found'];                      // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that a category was found and deleted
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Category deleted.',
        'data'      =>  $categoryArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the category's data
?>