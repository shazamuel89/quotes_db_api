<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../functions/controller.php';
    require_once __DIR__ . '/../../models/Category.php';

    // Declare and initialize objects we are using
    $database = new Database();                                 // Instantiate a Database object
    $db = $database->connect();                                 // Get the connection from the Database object
    $category = new Category($db);                              // Instantiate a Category object that has the connection to the Database object
    
    // Execute request
    try {
        $result = $category->read();                            // Read categories and get result
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
    $categoriesArr = $result->fetchAll(PDO::FETCH_ASSOC);       // Get array of rows, each row as an associative array with key/value being the column/value
    
    // Verify results were fetched
    if (count($categoriesArr) === 0) {                          // If there were no results from read query
        echo json_encode([
            'message'   =>  'No Categories Found'               // Output error message
        ]);
        exit();                                                 // Exit script
    }                                                           // Verified that rows were found
    
    // Output results
    echo json_encode($categoriesArr);                           // Output in json the array of rows
?>