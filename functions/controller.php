<?php
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
    
    function verifyResult($resultArr, $userMessage) {
        if ($resultArr['success'] === false) {                                  // If query failed
            $errorTypeArr = $errorTypesData[$resultArr['error type']];          // Get individual error type's data
            if (array_key_exists('message', $resultArr)) {                      // If the result array had an error code attached to it
                return getError($errorTypeArr, $userMessage, $errorMessage);    // Return the error with the error code
            }                                                                   // Verified the result array had no error code
            return getError($errorTypeArr, $userMessage);                       // Return the error
        }                                                                       // Verified the query was a success
        return true;                                                            // Return true
    }

    function getError($errorTypeArr, $userMessage, $errorMessage = '') {
        http_response_code($errorTypeArr['http_response_code']);        // Set HTTP Status Code to appropriate value
        return json_encode([                                            // Return a json encoded array
            'message'   =>  $userMessage,                               // With a user readable message
            'error'     =>  $errorTypeArr['message'] . $errorMessage    // And the situational message concatenated with the specific error message if provided
        ]);
    }
?>