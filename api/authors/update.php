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
    require_once __DIR__ . '/../../models/Author.php';

    define('USER_MESSAGE', 'Author update failed.');                                // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                                        // If the id value wasn't provided
        $errorTypeArr = $errorTypesData['missing id parameter'];                    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified id parameter was provided
    if (!isset($data->author)) {                                                    // If the author value wasn't provided
        $errorTypeArr = $errorTypesData['missing author parameter'];                // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified author parameter was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    $author->setId($data->id);                                                      // Put id value from request into Author object (and sanitize it)
    $author->setAuthor($data->author);                                              // Put author value from request into Author object (and sanitize it)
    $resultArr = $author->update();                                                 // Update author entry and get result array
    if ($resultArr['success'] === false) {                                          // If query failed
        $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE, $resultArr['message'] ?? ''));    // Return the error
    }                                                                               // Verified the query was a success
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);                       // Get the updated row
    if ($authorArr === false) {                                                     // If query fetch returned false (meaning the id input did not match an author's id)
        $errorTypeArr = $errorTypesData['no author found'];                         // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                                 // Kill script while outputting error message
    }                                                                               // Verified that author was found and updated
    http_response_code(200);                                                        // Set http status code to 200 for OK
    echo json_encode([
        'message'   =>  'Author updated.',
        'data'      =>  $authorArr
    ]);                                                                             // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>