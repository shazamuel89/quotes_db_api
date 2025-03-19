<?php
    /*
        executeQuery() accepts a PDOStatement object that has been prepared and already had its parameters bound to values.
        It executes the query, then returns an associative array that contains a boolean indicating success and another value that depends on the success.
        If the execution was a success, then the second value is the data, or the PDOStatement object that has just been executed.
        If the execution was a failure, then the second value is the error message from the thrown exception.
    */
    function executeQuery($stmt) {
        try {
            $stmt->execute();                       // Execute query
            return [                                // Return a result array
                'success' => true,                  // Indicating success
                'data'    => $stmt                  // And providing results of execution
            ];
        } catch (PDOException $e) {                 // If query failed
            return [                                // Return a result array
                'success'    => false,              // Indicating failure
                'error type' => 'execution error',  // Providing error message type
                'message'    => $e->getMessage()    // And providing error message
            ];
        }
    }

    /*
        validateForeignKey accepts a PDO object for the database connection, a table name, and an id to search for.
        It checks within the database to see if a row with the given id exists within the given table.
        If the query fails, then it returns an associative array that contains a boolean indicating success, and potentially another value depending on the result.
        If the execution was a success, then the returned array only contains the success boolean set to true.
        If the execution was a failure, then the returned array contains the success boolean set to false, an error type 'execution error' indicating the query execution failed, and a message containing the error message.
        If the execution was a success, but the query did not return any rows, then the success boolean is false, and an error type 'not found' indicating the query found no rows.
    */
    function validateForeignKey($conn, $table, $id) {
        $query = '
                SELECT
                    id
                FROM
                    ' . $table . '
                WHERE
                    id = :id;
            ';                                                              // This query is to check if the row with given id exists
            $stmt = $conn->prepare($query);                                 // Prepare statement
            $stmt->bindValue(':id', $id);                                   // Bind id value
            $resultArr = executeQuery($stmt);                               // Execute query, getting result array
            if ($resultArr['success'] === false) {                          // If select query failed
                return [                                                    // Return a result array
                    'success'    => false,                                  // Indicating failure
                    'error type' => 'execution error',                      // And indicating where the error occurred
                    'message'    => $resultArr['message']                   // And providing developer readable error message
                ];
            }                                                               // Validated select query succeeded
            if ($resultArr['data']->fetch(PDO::FETCH_ASSOC) === false) {    // If row with given id was not found
                return [                                                    // Return a result array
                    'success'    => false,                                  // Indicating failure
                    'error type' => 'not found'                             // And indicating where the error occurred
                ];
            }                                                               // Validated given id matches an existing row
            return [                                                        // Return the result array
                'success' => true                                           // Indicating success, no need to provide anything else
            ];
    }
?>