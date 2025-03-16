<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    $database = new Database();                                         // Instantiate a Database object
    $db = $database->connect();                                         // Get the connection from the Database object
    $author = new Author($db);                                          // Instantiate an Author object that has the connection to the Database object
    $result = $author->read();                                          // Get $stmt (or false) from read() and put into $result
    if ($result === false) {                                            // If read() had an error
        http_response_code(500);                                        // Then set HTTP Status Code to 500 for Internal Server Error
        die(json_encode(['message' => 'Authors read failed.']));        // Output failure message in json and kill script
    }                                                                   // Verified that read() worked (not necessarily that it returned any rows)
    $authors_arr = $result->fetchAll(PDO::FETCH_ASSOC);                 // Get array of rows, each row as an associative array with key/value being the column/value
    if (count($authors_arr) === 0) {                                    // If there were no results from read query
        http_response_code(404);                                        // Then set HTTP Status Code to 404 for Not Found
        die(json_encode(['message' => 'No authors found.']));           // Output no authors found message and kill script
    }                                                                   // Verified that rows were found
    echo json_encode([
        'message' => 'Authors found.',
        'data'    => $authors_arr
    ]);                                                                 // Output in json an array where the key 'data' is pointing to a value which is the array of rows
?>