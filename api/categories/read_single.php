<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Require statements (using __DIR__ absolute paths to ensure they are correct)
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Category.php';

    // Verify input parameters were provided
    if (!isset($_GET['id'])) {                                                  // If $_GET superglobal does not contain necessary id
        echo json_encode([
            'message'   =>  'Missing Required Parameters'                       // Output error message
        ]);
        exit();                                                                 // Exit script
    }                                                                           // Verified that ID was provided
    
    // Declare and initialize objects we are using
    $database = new Database();                                                 // Instantiate a Database object
    $db = $database->connect();                                                 // Get the connection from the Database object
    $category = new Category($db);                                              // Instantiate a Category object that has the connection to the Database object
    $category->setId($_GET['id']);                                              // Set Category object's id (and sanitize it)
    
    // Execute request
    try {
        $result = $category->read_single();                                     // Lookup category entry and get result
    } catch (PDOException $e) {                                                 // If an error occurred
        echo json_encode([
            'message'   =>  'A database error occurred: ' . $e->getMessage()    // Output the error message
        ]);
        exit();                                                                 // And exit the script
    } catch (Exception $e) {                                                    // If another error occurred
        echo json_encode([
            'message'   =>  $e->getMessage()                                    // Output the error message
        ]);
        exit();                                                                 // And exit the script
    }
    
    // Fetch results
    $categoryArr = $result->fetch(PDO::FETCH_ASSOC);                            // Fetch the result of read_single() as an associative array (or false if no rows)
    
    // Verify results were fetched
    if ($categoryArr === false) {                                               // If there were no rows found matching the id
        echo json_encode([
            'message'   =>  'category_id Not Found'                             // Output error message
        ]);
        exit();                                                                 // Exit script
    }                                                                           // Verified that a row was found
    
    // Output results
    echo json_encode($categoryArr);                                             // Output in json an array containing the category's data
?>