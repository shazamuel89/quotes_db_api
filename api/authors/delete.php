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

    // Get input parameters
    $input = json_decode(file_get_contents("php://input"));     // Get JSON data of client's request from php://input, decode it into an object
    
    // Verify input parameters were provided
    if (!isset($input->id)) {                                   // If the id value wasn't provided
        echo json_encode([
            'message'   =>  'Missing Required Parameters'       // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified id parameter was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $author = new Author($db);                                  // Instantiate an Author object that has the connection to the Database object
    $author->setId($input->id);                                 // Put id value from request into Author object (and sanitize it)
    
    // Execute request
    try {
        $result = $author->delete();                            // Delete author entry and get result
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
    $authorArr = $result->fetch(PDO::FETCH_ASSOC);              // Get the deleted row from the PDOStatement object in the result
    
    // Verify results were fetched
    if ($authorArr === false) {                                 // If query fetch returned false (meaning the id input did not match an author's id)
        echo json_encode([
            'message'   =>  'No Authors Found'                  // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified that an author was found and deleted
    
    // Output results
    echo json_encode([
        'id'    =>  $authorArr['id']                            // Output in json an array containing the author's id value
    ]);
?>