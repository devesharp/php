<?php

return [
    'APIDocs' => [
        'name' => "API " . env('name'),
        'description' => "",
        'servers' => [
            'url' => 'dev',
            'description' => 'https://dev.api.com.br',
        ],
        'save_file' => 'api-docs.yml'
    ]
];
