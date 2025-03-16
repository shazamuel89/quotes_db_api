<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    if (!isset($_GET['id'])) {                                          // If $_GET superglobal does not contain necessary id
        http_response_code(400);                                        // Then set HTTP Status Code to 400 for Bad Request
        die(json_encode(['message' => 'Missing ID']));                  // Output Missing ID json message and kill script
    }                                                                   // Verified that ID was provided
    $database = new Database();                                         // Instantiate a Database object
    $db = $database->connect();                                         // Get the connection from the Database object
    $author = new Author($db);                                          // Instantiate an Author object that has the connection to the Database object
    $author->setId($_GET['id']);                                        // Set author object's id (and sanitize it)
    $result = $author->read_single();                                   // Get $stmt (or false) from read_single() and put into $result
    if ($result === false) {                                            // If read_single() had an error
        http_response_code(500);                                        // Then set HTTP Status Code to 500 for Internal Server Error
        die(json_encode(['message' => 'Author lookup failed.']));       // Output failure message in json and kill script
    }                                                                   // Verified that read_single() worked (not necessarily that it returned a row)
    $author_arr = $result->fetch(PDO::FETCH_ASSOC);                     // Fetch the result of read_single() as an associative array (or false if no rows)
    if ($author_arr === false) {                                        // If there were no rows found matching the id
        http_response_code(404);                                        // Then set HTTP Status Code to 404 for Not Found
        die(json_encode(['message' => 'Author not found']));            // Then output author not found message and kill script
    }                                                                   // Verified that a row was found
    echo json_encode([
        'message' => 'Author found.',
        'data'    => $author_arr
    ]);                                                                 // Output in json an array where the key 'data' is pointing to a value which is the author's data
?>