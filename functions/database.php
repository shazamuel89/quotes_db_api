<?php
    /*
        executeQuery() accepts a PDOStatement object that has been prepared and already had its parameters bound to values.
        It executes the query, then returns an associative array that contains a boolean indicating success and another value that depends on the success.
        If the execution was a success, then the second value is the data, or the PDOStatement object that has just been executed.
        If the execution was a failure, then the second value is the error message from the thrown exception.
    */
    function executeQuery($stmt) {
        try {
            $stmt->execute();                   // Execute query
            return [                            // Return a result array
                'success' => true,              // Indicating success
                'data'    => $stmt              // And providing results of execution
            ];
        } catch (PDOException $e) {             // If query failed
            return [                            // Return a result array
                'success' => false,             // Indicating failure
                'message' => $e->getMessage()   // And providing error message
            ];
        }
    }
?>