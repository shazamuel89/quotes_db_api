<?php
    header('Access-Control-Allow-Origins: *');
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];                                                           // Get the request method

    switch ($method) {
        case 'OPTIONS':                                                                             // For request method OPTIONS
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');                         // Allow main methods
            header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With'); // Allow headers
            exit();                                                                                 // Stop script
            break;
        case 'GET':                                                                                 // For request method GET
            if (isset($_GET['id']) && !empty($_GET['id'])) {                                        // If the request provided an id specification, then they needed a specific row
                require_once 'read_single.php';
            } else {                                                                                // If no id was provided, then they need all rows of a certain filter or no filter
                require_once 'read.php';
            }
            break;
        case 'POST':                                                                                // For request method POST
            require_once 'create.php';
            break;
        case 'PUT':                                                                                 // For request method PUT
            require_once 'update.php';
            break;
        case 'DELETE':                                                                              // For request method DELETE
            require_once 'delete.php';
            break;
        default:
            echo 'Not a supported request method.';
    }
?>