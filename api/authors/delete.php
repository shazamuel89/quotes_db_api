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

    $data = json_decode(file_get_contents("php://input"));                  // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->id)) {                                                // If the id value wasn't provided
        http_response_code(400);                                            // Then set HTTP Status Code to 400 for Bad Request
        die(json_encode(['message' => 'Missing required id parameter']));   // Output missing id json message and kill script
    }                                                                       // Verified id parameter was provided
    $database = new Database();                                             // Instantiate a Database object
    $db = $database->connect();                                             // Get the connection from the Database object
    $author = new Author($db);                                              // Instantiate an Author object that has the connection to the Database object
    $author->setId($data->id);                                              // Put id value from request into author object (and sanitize it)
    $resultArr = $author->delete();                                         // Delete author entry and get result array
    if ($resultArr['success'] === false) {                                  // If deletion failed
        http_response_code(500);                                            // Then set HTTP Status Code to 500 for Internal Server Error
        die(json_encode([                                                   // Kill script while displaying a json encoded array
            'message' => 'Author deletion failed.',                         // With a user readable message
            'error'   => $resultArr['message']                              // And a developer readable error message
        ]));
    }                                                                       // Verified that author deletion succeeded
    $author_arr = $resultArr['data']->fetch(PDO::FETCH_ASSOC);              // Get the deleted row from the PDOStatement object in the result array
    if ($author_arr === false) {                                            // If query fetch returned false (meaning the id input did not match an author's id)
        http_response_code(404);                                            // Then set HTTP Status Code to 404 for Not Found
        die(json_encode(['message' => 'No author found.']));                // Output no author found message and kill script
    }
    echo json_encode([
        'message' => 'Author deleted.',
        'data'    => $author_arr
    ]);                                                                     // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>