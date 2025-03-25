<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Quote.php';

    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $quote = new Quote($db);                                    // Instantiate a Quote object that has the connection to the Database object
    
    // Initialize filters if they were applied
    if (isset($_GET['author_id'])) {                            // If $_GET superglobal contains optional author_id filter
        $quote->setAuthor_id($_GET['author_id']);               // Then set the Quote object's author_id property to the author_id to filter by
    }                                                           // Verified author_id filter is accounted for
    if (isset($_GET['category_id'])) {                          // If $_GET superglobal contains optional category_id filter
        $quote->setCategory_id($_GET['category_id']);           // Then set the Quote object's category_id property to the category_id to filter by
    }                                                           // Verified category_id filter is accounted for
    
    // Execute request
    try {
        $result = $quote->read();                               // Read quotes and get result
    } catch (PDOException $e) {                                 // If an error occurred
        echo json_encode([
            'message'   =>  'A database error occurred: ' . $e  // Output the error message
        ]);
        exit();                                                 // And exit the script
    } catch (Exception $e) {                                    // If another error occurred
        echo json_encode([
            'message'   =>  $e                                  // Output the error message
        ]);
        exit();                                                 // And exit the script
    }
    
    // Fetch results
    $quotesArr = $result->fetchAll(PDO::FETCH_ASSOC);           // Get array of rows, each row as an associative array with key/value being the column/value
    
    // Verify results were fetched
    if (count($quotesArr) === 0) {                              // If there were no results from read query
        echo json_encode([
            'message'   =>  'No Quotes Found'                   // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified that rows were found
    
    // Output results
    echo json_encode($quotesArr);                               // Output in json an array containing the array of rows
?>