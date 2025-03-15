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

    $data = json_decode(file_get_contents("php://input"));                      // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                                    // If the id value wasn't provided
        http_response_code(400);                                                // Then set HTTP Status Code to 400 for Bad Request
        die(json_encode(array('message' => 'Missing required id parameter')));  // Output missing id json message and kill script
    }                                                                           // Verified id parameter was provided
    $database = new Database();                                                 // Instantiate a Database object
    $db = $database->connect();                                                 // Get the connection from the Database object
    $author = new Author($db);                                                  // Instantiate an Author object that has the connection to the Database object
    $author->setId($data->id);                                                  // Put id value from request into author object (and sanitize it)
    $result = $author->delete();                                                // Delete author entry and get result
    if ($result === false) {                                                    // If deletion failed
        http_response_code(500);                                                // Then set HTTP Status Code to 500 for Internal Server Error
        die(json_encode(array('message' => 'Author deletion failed.')));        // Output failure message in json
    }                                                                           // Verified that author deletion succeeded
    $row = $result->fetch(PDO::FETCH_ASSOC);                                    // Get the deleted row
    $author_arr = array(                                                        // Create an array containing deleted author's data
        'id' => $row['id']                                                      // Put in author's id value
    );
    echo json_encode(['data' => $author_arr]);                                  // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>