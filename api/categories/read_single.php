<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Category.php';

    define('USER_MESSAGE', 'Category lookup failed.');                              // This is a constant that defines what user readable message is output for errors

    if (!isset($_GET['id'])) {                                                      // If $_GET superglobal does not contain necessary id
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that ID was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $category = new Category($db);                                                  // Instantiate a Category object that has the connection to the Database object
    $category->setId($_GET['id']);                                                  // Set Category object's id (and sanitize it)
    $resultArr = $category->read_single();                                          // Get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $categoryArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                     // Fetch the result of read_single() as an associative array (or false if no rows)
    if ($categoryArr === false) {                                                   // If there were no rows found matching the id
        $errorTypeArr = $errorTypesData['category not found'];                      // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that a row was found
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Category found.',
        'data'      =>  $categoryArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the category's data
?>