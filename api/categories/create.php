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

    // Get input parameters
    $input = json_decode(file_get_contents("php://input"));     // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($input->category)) {                             // If the category value wasn't provided
        echo json_encode([
            'message'   =>  'Missing Required Parameters'       // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified category parameter was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $category = new Category($db);                              // Instantiate a Category object that has the connection to the Database object
    $category->setCategory($input->category);                   // Put category value from request into Category object (and sanitize it)
    
    // Execute request
    try {
        $result = $category->create();                          // Create category entry and get result
    } catch (PDOException $e) {                                 // If an error occurred
        echo json_encode([
            'message'   =>  'A database error occurred: ' . $e  // Output the error message
        ]);
        exit();                                                 // And exit the script
    } catch (Exception $e) {                                    // If another error occurred
        echo json_encode([
            'message'   =>  $e                                  // Output the error message
        ]);
        exit();                                                 // And exit the script
    }
    
    // Fetch results
    $categoryArr = $result->fetch(PDO::FETCH_ASSOC);            // Get the newly created row from the PDOStatement object in the result
    
    // No need to verify results were fetched, because if create query didn't return a result, then create query must have failed which would have been caught earlier

    // Output results
    echo json_encode($categoryArr);                             // Output in json an array containing the category's data
?>