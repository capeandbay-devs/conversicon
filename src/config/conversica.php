<?php

return [
    'api_version' => env('CONVERSICA_API_VERSION', '7.1'),
    'route_prefix' => 'conversica',
    'message_driver' => env('CONVERSICON_MSG_DRIVER', 'log'),
    'lead_update_driver' => env('CONVERSICON_MSG_DRIVER', 'log'),
    'message_job_queue' => [
        'queue' => env('CONVERSICON_MSG_JOB_Q', ''),
        'class' => env('CONVERSICON_MSG_JOB_CLASS', '')
    ],
    'lead_job_queue' => [
        'queue' => env('CONVERSICON_LEAD_JOB_Q', ''),
        'class' => env('CONVERSICON_LEAD_JOB_CLASS', '')
    ],
    'deets' => [
        'username' => env('CONVERSICA_API_USERNAME',''),
        'password' => env('CONVERSICA_API_PASSWORD',''),
    ]
];
