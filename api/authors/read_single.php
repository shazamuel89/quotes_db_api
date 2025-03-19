<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    define('USER_MESSAGE', 'Author lookup failed.');                // This is a constant that defines what user readable message is output for errors

    if (!isset($_GET['id'])) {                                      // If $_GET superglobal does not contain necessary id
        $errorTypeArr = $errorTypesData['missing id parameter'];    // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                 // Kill script while outputting error message
    }                                                               // Verified that ID was provided
    $database = new Database();                                     // Instantiate a Database object
    $db = $database->connect();                                     // Get the connection from the Database object
    $author = new Author($db);                                      // Instantiate an Author object that has the connection to the Database object
    $author->setId($_GET['id']);                                    // Set author object's id (and sanitize it)
    $resultArr = $author->read_single();                            // Get result array
    $result = checkResult($resultArr, USER_MESSAGE);                // Check the result, if bad result then get json encoded error message
    if ($result !== true) {                                         // If no success
        die($result);                                               // Then kill script, outputting error message
    }                                                               // Verified author lookup succeeded (not necessarily that it returned a row)
    $authorArr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);       // Fetch the result of read_single() as an associative array (or false if no rows)
    if ($authorArr === false) {                                     // If there were no rows found matching the id
        $errorTypeArr = $errorTypesData['author not found'];        // Get individual error type's data
        die(getError($errorTypeArr, USER_MESSAGE));                 // Kill script while outputting error message
    }                                                               // Verified that a row was found
    echo json_encode([
        'message'   =>  'Author found.',
        'data'      =>  $authorArr
    ]);                                                             // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>