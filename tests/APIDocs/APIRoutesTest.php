<?php

namespace Tests\APIDocs;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;

class APIRoutesTest extends \Tests\TestCase
{
    use \Devesharp\Testing\TestCase;

    protected function setUp(): void
    {
        parent::setUp();

        \Devesharp\APIDocs\APIDocsCreate::getInstance()->init();

        \Route::middleware([])->post('/resource', function () {
            return [
                'results' => [
                    [
                        'id' => 1,
                        'name' => 'John',
                        'age' => '10',
                    ]
                ],
                'count' => 1,
            ];
        });
        \Route::middleware([])->get('/resource/:id', function () {
            return [
                'id' => 1,
                'name' => 'John',
                'age' => '10',
            ];
        });
        \Route::middleware([])->delete('/resource/:id', function () {
            return [
                'id' => 1,
                'deleted' => true
            ];
        });
    }

    public function testSimplePost()
    {
        $response = $this->withPost([
            'name' => 'Create Post',
            'group' => ['Resources'],
            'uri' => '/resource',
            'data' => [
                'name' => 'John',
                'age' => 'John',
            ],
        ]);

        $responseDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance()->toYml();

        $this->assertEquals($responseDocs, "openapi: 3.0.2
info:
  title: 'API Docs'
  description: 'API Docs'
  version: '1.0'
servers: []
paths:
  /resource:
    post:
      tags:
        - Resources
      summary: 'Create Post'
      description: ''
      parameters: []
      responses:
        '200':
          description: 'Resposta com sucesso'
          content:
            application/json:
              schema:
                type: object
                properties:
                  results:
                    type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: integer
                          example: 1
                        name:
                          type: string
                          example: John
                        age:
                          type: string
                          example: '10'
                    example:
                      -
                        id: 1
                        name: John
                        age: '10'
                  count:
                    type: integer
                    example: 1
");

    }

    public function testSimpleGet()
    {
        $response = $this->withGet([
            'name' => 'Get Resource',
            'group' => ['Resources'],
            'uri' => '/resource/:id',
            'params' => [
                [
                    'name' => 'id',
                    'value' => 1,
                    'description' => 'id resource',
                ]
            ],
            'queries' => [
                [
                    'name' => 'showAll',
                    'value' => 1,
                    'description' => 'show all items',
                ]
            ],
            'data' => [
                'name' => 'John',
                'age' => 'John',
            ],
        ]);

        $responseDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance()->toYml();

        $this->assertEquals($responseDocs, "openapi: 3.0.2
info:
  title: 'API Docs'
  description: 'API Docs'
  version: '1.0'
servers: []
paths:
  '/resource/{id}':
    get:
      tags:
        - Resources
      summary: 'Get Resource'
      description: ''
      parameters:
        -
          name: id
          in: path
          description: 'id resource'
          required: true
          schema:
            type: integer
          example: 1
        -
          name: showAll
          in: query
          description: 'show all items'
          required: false
          schema:
            type: integer
          example: 1
      responses:
        '200':
          description: 'Resposta com sucesso'
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:
                    type: integer
                    example: 1
                  name:
                    type: string
                    example: John
                  age:
                    type: string
                    example: '10'
");

    }

    public function testSimpleDelete()
    {
        $response = $this->withDelete([
            'name' => 'Delete Resource',
            'group' => ['Resources'],
            'uri' => '/resource/:id',
            'params' => [
                [
                    'name' => 'id',
                    'value' => 1,
                    'description' => 'id resource',
                ]
            ],
            'queries' => [
                [
                    'name' => 'showAll',
                    'value' => 1,
                    'description' => 'show all items',
                ]
            ],
            'data' => [
                'name' => 'John',
                'age' => 'John',
            ],
        ]);

        $responseDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance()->toYml();

        $this->assertEquals($responseDocs, "openapi: 3.0.2
info:
  title: 'API Docs'
  description: 'API Docs'
  version: '1.0'
servers: []
paths:
  '/resource/{id}':
    delete:
      tags:
        - Resources
      summary: 'Delete Resource'
      description: ''
      parameters:
        -
          name: id
          in: path
          description: 'id resource'
          required: true
          schema:
            type: integer
          example: 1
        -
          name: showAll
          in: query
          description: 'show all items'
          required: false
          schema:
            type: integer
          example: 1
      responses:
        '200':
          description: 'Resposta com sucesso'
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:
                    type: integer
                    example: 1
                  deleted:
                    type: boolean
                    example: true
");

    }

    public function testSimplePostWithValidator()
    {
        $response = $this->withPost([
            'name' => 'Create Post',
            'group' => ['Resources'],
            'uri' => '/resource',
            'data' => [
                'name' => 'John',
                'age' => 1,
                'item' => [
                    'id' => 1
                ]
            ],
            'validatorClass' => \Tests\APIDocs\Mocks\ValidatorStub::class,
            'validatorMethod' => 'create'
        ]);

        $responseDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance()->toYml();

        var_dump($responseDocs);

        $this->assertEquals($responseDocs, "openapi: 3.0.2
info:
  title: 'API Docs'
  description: 'API Docs'
  version: '1.0'
servers: []
paths:
  /resource:
    post:
      tags:
        - Resources
      summary: 'Create Post'
      description: ''
      parameters: []
      responses:
        '200':
          description: 'Resposta com sucesso'
          content:
            application/json:
              schema:
                type: object
                properties:
                  results:
                    type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: integer
                          example: 1
                        name:
                          type: string
                          example: John
                        age:
                          type: string
                          example: '10'
                    example:
                      -
                        id: 1
                        name: John
                        age: '10'
                  count:
                    type: integer
                    example: 1
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  example: string
                age:
                  type: integer
                  example: 1
                item:
                  type: object
                  properties:
                    id:
                      type: integer
                      example: 1
                active:
                  type: boolean
                  example: false
                properties:
                  type: array
                  items:
                    type: object
                    properties:
                      id:
                        type: integer
                        example: 1
                  example:
                    -
                      id: 1
");

    }
}
