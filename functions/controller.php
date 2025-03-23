<?php
    /*
        This array is meant to centralize and simplify the error feedback for this api.
        The api scripts can access specific messages and http_response_codes for various situations using the defined error types as keys.
        There may be a better, more procedural way to do it if the number of error types gets too large, but for this scope, it is not necessarily worth it.
    */
    $errorTypesData = [
        'execution error'                           =>  [
                                                            'http_response_code'    =>  500,
                                                            'message'               =>  'A database error occurred: '
                                                        ],
        'author_id validation execution error'      =>  [
                                                            'http_response_code'    =>  500,
                                                            'message'               =>  'A database error occurred while validating the author_id: '
                                                        ],
        'category_id validation execution error'    =>  [
                                                            'http_response_code'    =>  500,
                                                            'message'               =>  'A database error occurred while validating the category_id: '
                                                        ],
        'author_id not matching'                    =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'No matching author found for author_id.'
                                                        ],
        'category_id not matching'                  =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'No matching category found for category_id.'
                                                        ],
        'missing id parameter'                      =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required id parameter.'
                                                        ],
        'missing author parameter'                  =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required author parameter.'
                                                        ],
        'missing category parameter'                =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required category parameter.'
                                                        ],
        'missing quote parameter'                   =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required quote parameter.'
                                                        ],
        'missing author_id parameter'               =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required author_id parameter.'
                                                        ],
        'missing category_id parameter'             =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'Missing required category_id parameter.'
                                                        ],
        'author not found'                          =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'No author found.'
                                                        ],
        'category not found'                        =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'No category found.'
                                                        ],
        'quote not found'                           =>  [
                                                            'http_response_code'    =>  400,
                                                            'message'               =>  'No quote found.'
                                                        ]
    ];
    
    /*
        verifyResult accepts an associative array with a key 'success' that is a boolean.
        It also takes a string for the user message.
        If the array's 'success' value is false, then the function gets the error type, calls the getError function, and returns the result.
        If the array's 'success' value is true, then the function simply returns true.
    */
    function verifyResult($resultArr, $userMessage) {
        if ($resultArr['success'] === false) {                                          // If query failed
            $errorTypeArr = $errorTypesData[$resultArr['error type']];                  // Get individual error type's data
            return getError($errorTypeArr, $userMessage, $resultArr['message'] ?? '');  // Return the error
        }                                                                               // Verified the query was a success
        return true;                                                                    // Return true
    }

    /*
        getError accepts an associative array containing the specific error type's information, a string for the user message, and an optional error message string.
        It sets the correct http_response_code for the error type, and then it returns the json encoded array containing the information needed to give feedback for the error.
    */
    function getError($errorTypeArr, $userMessage, $errorMessage = '') {
        http_response_code($errorTypeArr['http_response_code']);        // Set HTTP Status Code to appropriate value
        return json_encode([                                            // Return a json encoded array
            'message'   =>  $userMessage,                               // With a user readable message
            'error'     =>  $errorTypeArr['message'] . $errorMessage    // And the situational message concatenated with the specific error message if provided
        ]);
    }
?>