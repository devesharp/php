<?php

namespace Tests\APIDocs\Mocks;

use Devesharp\CRUD\Validator;

class DicStub extends \Devesharp\APIDocs\Dictionary
{
    protected $apiDic = [
        'Resources' => 'Associações'
    ];

    protected $replaceBodyKeys = [
      'create' => [
          'name' => 'Leona',
          'age' => '$AgeType',
      ]
    ];

    protected $replaceResponseKeys = [
        'create' => [
            'name' => 'Leo'
        ]
    ];

    protected $addAPIComponents = [
        'AgeType' => [
            'type' => 'string',
            'description' => '',
            'enum' => [
                'vehicles',
                'events'
            ],
        ]
    ];
}
