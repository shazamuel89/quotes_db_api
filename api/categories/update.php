<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Category.php';

    // Get input parameters
    $input = json_decode(file_get_contents("php://input"));     // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($input->id) || !isset($input->category)) {       // If the id or category values weren't provided
        echo json_encode([
            'message'   =>  'Missing Required Parameters'       // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified id and category parameters were provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $category = new Category($db);                              // Instantiate a Category object that has the connection to the Database object
    $category->setId($input->id);                               // Put id value from request into Category object (and sanitize it)
    $category->setCategory($input->category);                   // Put category value from request into Category object (and sanitize it)
    
    // Execute request
    try {
        $result = $category->update();                          // Update category entry and get result
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
    $categoryArr = $result->fetch(PDO::FETCH_ASSOC);            // Get the updated row
    
    // Verify results were fetched
    if ($categoryArr === false) {                               // If query fetch returned false (meaning the id input did not match a category's id)
        echo json_encode([
            'message'   =>  'No Categories Found'               // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified that category was found and updated
    
    // Output results
    echo json_encode($categoryArr);                             // Output in json an array containing the category's data
?>