<?php
    /*
        validateForeignKey accepts a PDO object for the database connection, a table name, and an id to search for.
        It checks within the database to see if a row with the given id exists within the given table.
        If the query did not return any rows, then it returns false.
        If the query returned at least one row, then it returns true.
    */
    function validateForeignKey($conn, $table, $id) {
        $query = '
                SELECT
                    id
                FROM
                    ' . $table . '
                WHERE
                    id = :id;
            ';                                                  // This query is to check if the row with given id exists
            $stmt = $conn->prepare($query);                     // Prepare statement
            $stmt->bindValue(':id', $id);                       // Bind id value
            if ($stmt->execute()) {                             // If stmt executes successfully
                if ($stmt->fetch(PDO::FETCH_ASSOC) === false) { // If row with given id was not found
                    return false;                               // Return false
                }                                               // Validated given id matches an existing row
                return true;                                    // Return true for row found
            }
    }
?>