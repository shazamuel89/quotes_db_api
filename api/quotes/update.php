<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers:
            Access-Control-Allow-Headers,
            Content-Type,
            Access-Control-Allow-Methods,
            Authorization,
            X-Requested-With');
    
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Quote.php';

    define('USER_MESSAGE', 'Quote update failed.');                                 // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                                        // If the id value wasn't provided
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified id parameter was provided
    if (!isset($data->quote)) {                                                     // If the quote value wasn't provided
        $errorTypeArr = $errorTypesData['missing quote parameter'];                 // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified quote parameter was provided
    if (!isset($data->author_id)) {                                                 // If the author_id value wasn't provided
        $errorTypeArr = $errorTypesData['missing author_id parameter'];             // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified author_id parameter was provided
    if (!isset($data->category_id)) {                                               // If the category_id value wasn't provided
        $errorTypeArr = $errorTypesData['missing category_id parameter'];           // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified category_id parameter was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $quote = new Quote($db);                                                        // Instantiate a Quote object that has the connection to the Database object
    $quote->setId($data->id);                                                       // Put id value from request into Quote object (and sanitize it)
    $quote->setQuote($data->quote);                                                 // Put quote value from request into Quote object (and sanitize it)
    $quote->setAuthor_id($data->author_id);                                         // Put author_id value from request into Quote object (and sanitize it)
    $quote->setCategory_id($data->category_id);                                     // Put category_id value from request into Quote object (and sanitize it)
    $resultArr = $quote->update();                                                  // Update quote entry and get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $quoteArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                        // Get the updated row
    if ($quoteArr === false) {                                                      // If query fetch returned false (meaning the id input did not match a quote's id)
        $errorTypeArr = $errorTypesData['no quote found'];                          // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that quote was found and updated
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Quote updated.',
        'data'      =>  $quoteArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the quote's data
?>