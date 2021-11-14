<?php

namespace Tests\APIDocs;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;

class APIDocsCreateTest extends \Tests\TestCase
{
    public function testHeaderDocs()
    {
        $apiDocs = new \Devesharp\APIDocs\APIDocsCreate();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->addServers('https://example.com.br', 'Prod API');
        $apiDocs->init();

        $openApi = new \cebe\openapi\spec\OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([
                "title" => 'API 1.0',
                "description" => 'API Example',
                "version" => "1.0",
            ]),
            "servers" => [
                [
                    'url' => 'https://example.com.br',
                    'description' => 'Prod API'
                ]
            ],
            'paths' => [],
        ]);

        $this->assertEquals(\cebe\openapi\Writer::writeToYaml($openApi), $apiDocs->toYml());
    }

    public function testPost()
    {
        $apiDocs = new \Devesharp\APIDocs\APIDocsCreate();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->addServers('https://example.com.br', 'Prod API');
        $apiDocs->addDefaultResponse([
            'status' => '504',
            'body' => [
                'data' => [
                    'name' => 'sdsd',
                    'b' => '$itemEnum',
                ]
            ],
        ], '*', '/^(.*)a$/');

        $apiDocs->init();

        $apiDocs->addRef('itemEnum', [
            'type' => 'number',
            'description' => '1 = "A", 2 = "B", 3 = "C", 4 = "D", 5 = "E", 6 = "AB", 7 = "AC", 8 = "AD", 9 = "AE"',
            'enum' => [
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
            ],
        ]);

        $apiDocs->addRoute([
            'group' => ['Associate'],
            'summary' => 'Create Associate',
            'method' => 'get',
            'uri' => '/api/{sdsd}',
            'queries' => [
                [
                    'name' => 'sdsd',
                    'value' => 1,
                ]
            ],
            'params' => [
                [
                    'name' => 'sdsd',
                    'value' => 1,
                ]
            ],
            'response' => [
                'status' => '200',
                'body' => [
                    'data' => [
                        'name' => 'sdsd',
                        'b' => '$itemEnum',
                    ]
                ],
            ]
        ]);

        $openApi = new \cebe\openapi\spec\OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([
                "title" => 'API 1.0',
                "description" => 'API Example',
                "version" => "1.0",
            ]),
            "servers" => [
                [
                    'url' => 'https://example.com.br',
                    'description' => 'Prod API'
                ]
            ],
            'paths' => [],
        ]);

        var_dump($apiDocs->toYml());

//        $this->assertEquals(\cebe\openapi\Writer::writeToYaml($openApi), $apiDocs->toYml());
    }

    public function testAllowResponseDefault()
    {
        $apiDocs = new \Devesharp\APIDocs\APIDocsCreate();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->addServers('https://example.com.br', 'Prod API');
        $apiDocs->addDefaultResponse([
            'status' => '404',
            'body' => [
                'data' => [
                    'name' => 'sdsd',
                    'b' => '$itemEnum',
                ]
            ],
        ], '*', '/^(.*)a$/');

        $apiDocs->init();

        $apiDocs->addRoute([
            'group' => ['Associate'],
            'summary' => 'Create Associate',
            'method' => 'get',
            'uri' => '/api/example',
            'queries' => [],
            'params' => [],
            'response' => [
                'status' => '200',
                'body' => [
                    'data' => [
                        'name' => 'sdsd',
                        'b' => '$itemEnum',
                    ]
                ],
            ]
        ]);

        $this->assertNotEmpty($apiDocs->toObject()['paths']['/api/example']['get']['responses']['404']);
    }

    public function testNotAllowResponseDefault()
    {
        $apiDocs = new \Devesharp\APIDocs\APIDocsCreate();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->addServers('https://example.com.br', 'Prod API');
        $apiDocs->addDefaultResponse([
            'status' => '404',
            'body' => [
                'data' => [
                    'name' => 'sdsd',
                    'b' => '$itemEnum',
                ]
            ],
        ], '*', '/^(.*)$/');

        $apiDocs->init();

        $apiDocs->addRoute([
            'group' => ['Associate'],
            'summary' => 'Create Associate',
            'method' => 'get',
            'uri' => '/api/example',
            'queries' => [],
            'params' => [],
            'response' => [
                'status' => '200',
                'body' => [
                    'data' => [
                        'name' => 'sdsd',
                        'b' => '$itemEnum',
                    ]
                ],
            ]
        ]);

        $this->assertFalse(isset($apiDocs->toObject()['paths']['/api/example']['get']['responses']['404']));
    }
}
