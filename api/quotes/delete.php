<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Quote.php';

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
    $quote = new Quote($db);                                    // Instantiate a Quote object that has the connection to the Database object
    $quote->setId($input->id);                                  // Put id value from request into Quote object (and sanitize it)
    
    // Execute request
    try {
        $result = $quote->delete();                             // Delete quote entry and get result
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
    $quoteArr = $result->fetch(PDO::FETCH_ASSOC);               // Get the deleted row from the PDOStatement object in the result
    
    // Verify results were fetched
    if ($quoteArr === false) {                                  // If query fetch returned false (meaning the id input did not match a quote's id)
        echo json_encode([
            'message'   =>  'No Quotes Found'                   // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified that a quote was found and deleted
    
    // Output results
    echo json_encode([
        'id'    =>  $quoteArr['id']
    ]);                                                         // Output in json an array containing the quote's id value
?>