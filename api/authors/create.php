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

    $data = json_decode(file_get_contents("php://input"));                          // Get JSON data of client's request from php://input, decode it into an object
    if (!isset($data->author)) {                                                    // If the author value wasn't provided
        http_response_code(400);                                                    // Then set HTTP Status Code to 400 for Bad Request
        die(json_encode(array('message' => 'Missing required author parameter')));  // Output missing author json message and kill script
    }                                                                               // Verified author parameter was provided
    $database = new Database();                                                     // Instantiate a Database object
    $db = $database->connect();                                                     // Get the connection from the Database object
    $author = new Author($db);                                                      // Instantiate an Author object that has the connection to the Database object
    $author->setAuthor($data->author);                                              // Put author value from request into author object (and sanitize it)
    $result = $author->create();                                                    // Create author entry and get result
    if ($result === false) {                                                        // If creation failed
        http_response_code(500);                                                    // Then set HTTP Status Code to 500 for Internal Server Error
        die(json_encode(array('message' => 'Author creation failed.')));            // Output failure message in json
    }                                                                               // Verified that author creation succeeded
    $row = $result->fetch(PDO::FETCH_ASSOC);                                        // Get the newly created row
    $author_arr = array(                                                            // Create an array containing newly created author's data
        'id' => $row['id'],                                                         // Put in new author's id value
        'author' => $row['author']                                                  // Put in new author's author value
    );
    echo json_encode(['data' => $author_arr]);                                      // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>