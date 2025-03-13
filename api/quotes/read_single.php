// This is only here to be a placeholder until I write the actual function
// if ($stmt === false) {                  // If query failed
//     return false;                       // Return false
// }                                       // Otherwise, if query was successful (doesn't mean it necessarily found results)
// $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch the result of the query as an associative array            
// if ($row) {                             // If there was a result found
//     $this->quote = $row['quote'];       // Assign values of row to object properties
//     $this->author_id = $row['author_id'];
//     $this->category_id = $row['category_id'];
//     return true;                        // Return true to indicate success and result found
// } else {                                // If no result was found
//     return false;                       // Return false to indicate no result found
// }