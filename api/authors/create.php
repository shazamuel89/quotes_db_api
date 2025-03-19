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
    require_once '../../models/Author.php';

    define('USER_MESSAGE', 'Author creation failed.');                  // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));              // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->author)) {                                        // If the author value wasn't provided
        $errorTypeArr = $errorTypesData['missing author parameter'];    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                     // Kill script while outputting error message
    }                                                                   // Verified author parameter was provided
    $database = new Database();                                         // Instantiate a Database object
    $db = $database->connect();                                         // Get the connection from the Database object
    $author = new Author($db);                                          // Instantiate an Author object that has the connection to the Database object
    $author->setAuthor($data->author);                                  // Put author value from request into author object (and sanitize it)
    $resultArr = $author->create();                                     // Create author entry and get result array
    $result = checkResult($resultArr, USER_MESSAGE);                    // Check the result, if bad result then get json encoded error message
    if ($result !== true) {                                             // If no success
        die($result);                                                   // Then kill script, outputting error message
    }                                                                   // Verified author creation succeeded
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);           // Get the newly created row from the PDOStatement object in the result array
    echo json_encode([
        'message'   =>  'Author created.',
        'data'      =>  $authorArr
    ]);                                                                 // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>