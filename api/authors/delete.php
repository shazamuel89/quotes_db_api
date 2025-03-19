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

    define('USER_MESSAGE', 'Author deletion failed.');              // This is a constant that defines what user readable message is output for errors

    $data = json_decode(file_get_contents("php://input"));          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                        // If the id value wasn't provided
        $errorTypeArr = $errorTypesData['missing id parameter'];    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                 // Kill script while outputting error message
    }                                                               // Verified id parameter was provided
    $database = new Database();                                     // Instantiate a Database object
    $db = $database->connect();                                     // Get the connection from the Database object
    $author = new Author($db);                                      // Instantiate an Author object that has the connection to the Database object
    $author->setId($data->id);                                      // Put id value from request into author object (and sanitize it)
    $resultArr = $author->delete();                                 // Delete author entry and get result array
    $result = checkResult($resultArr, USER_MESSAGE);                // Check the result, if bad result then get json encoded error message
    if ($result !== true) {                                         // If no success
        die($result);                                               // Then kill script, outputting error message
    }                                                               // Verified author deletion succeeded
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);       // Get the deleted row from the PDOStatement object in the result array
    if ($authorArr === false) {                                     // If query fetch returned false (meaning the id input did not match an author's id)
        $errorTypeArr = $errorTypesData['author not found'];        // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                 // Kill script while outputting error message
    }
    echo json_encode([
        'message'   =>  'Author deleted.',
        'data'      =>  $authorArr
    ]);                                                             // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>