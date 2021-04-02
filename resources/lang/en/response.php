<?php

return [
    'success' => [
        'create' => ':attribute created successfully!',
        'update' => ':attribute updated successfully!',
        'delete' => ':attribute deleted successfully!',
    ],
    'error' => [
        'create' => 'Error creating :attribute!',
        'update' => 'Error updating :attribute!',
        'delete' => 'Error deleting :attribute!',
    ],
    'db' => [
        'error' => 'An error occurred :attribute',
    ],
    'exception' => [
        'db' => 'Whoops, something went wrong, please try again!',
        'model' => 'Invalid request, data not found!',
        'http' => 'Invalid request, resource not found!',
        'internal' => 'Whoops, looks like something went wrong!'
    ]
];
