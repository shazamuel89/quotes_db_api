<?php
    function executeQuery($stmt) {
        try {
            $stmt->execute();                                   // Execute query
            return $stmt;                                       // If query worked without errors, return $stmt in case calling function needs it
        } catch (PDOException $e) {                             // If query failed
            error_log("Database Error: " . $e->getMessage());   // Log error message
            return false;                                       // Return false
        }
    }
?>