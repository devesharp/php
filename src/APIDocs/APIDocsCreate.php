<?php

namespace Devesharp\APIDocs;

use Illuminate\Console\Command;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;

class APIDocsCreate
{
    private static $instance;

    public $title = 'API Docs';

    public $description = 'API Docs';

    private OpenApi $openAPIJSON;

    private $servers = [];

    private $refs = [];

    private $refsEnabled = [];

    private $defaultsResponses = [];

    private $empty = true;

    private string $file = 'openapi-docs.yml';

    function init($increment = false) {
        if ($increment && file_exists($this->file)) {
            $this->openAPIJSON = \cebe\openapi\Reader::readFromYamlFile($this->file);
        } else {
            // create base API Description
            $this->openAPIJSON = new OpenApi([
                'openapi' => '3.0.2',
                'info' => new \cebe\openapi\spec\Info([
                    "title" => $this->title,
                    "description" => $this->description,
                    "version" => "1.0",
                ]),
                "servers" => $this->servers,
                'paths' => [],
            ]);
        }
    }

    function addRoute($info) {
        $this->empty = false;

        $tags = $info['group'];
        $method = $info['method'];
        $summary = $info['summary'] ?? '';
        $description = $info['description'] ?? '';
        $uri = $info['uri'];
        $params = $info['params'] ?? [];
        $queries = $info['queries'] ?? [];
        $body = $info['body'] ?? [];
        $bodyDescription = $info['bodyDescription'] ?? [];
        $bodyRequired = $info['bodyRequired'] ?? [];
        $ignoreBody = $info['ignoreBody'] ?? [];

        if (!empty($info['dicClass']) && class_exists($info['dicClass']) && isset($info['context'])) {
            $dic = app($info['dicClass']);
            $summary = $dic->replaceString($summary);
            \Devesharp\Support\Collection::make($dic->getAPIComponents())
                ->each(function ($value, $key) {
                    $this->setRefToKey($key, $value);
                });
            if ($tags) {
                $tags = \Devesharp\Support\Collection::make($tags)->map(fn($i) => $dic->replaceString($i))->toArray();
            }
        }

        //
        $responseStatus = $info['response']['status'];
        $responseDescription = !empty($info['response']['description']) ? $info['response']['description'] : 'Resposta com sucesso';
        $responseBody = $info['response']['body'] ?? [];
        $responseBodyRequired = $info['response']['bodyRequired'] ?? [];
        $responseIgnoreBody = $info['response']['ignoreBody'] ?? [];

        /**
         * Convert uri
         * convert /:id to /{id}
         */
        if (!empty($params)) {
            foreach ($params as $param) {
                $uri = str_replace(':' . $param['name'], '{' . $param['name'] . '}', $uri);
            }
        }

        /**
         * Convert params
         */
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                $params[$key] = [
                    'in' => 'path',
                    'name' => $param['name'],
                    'description' => $param['description'] ?? '',
                    'required' => $param['required'] ?? true,
                    'example' => 1,
                    'schema' => [
                        'type' => gettype($param['value']),
                    ]
                ];
            }
        }

        if (!empty($queries)) {
            foreach ($queries as $key => $query) {
                $queries[$key] = [
                    'in' => 'query',
                    'name' => $query['name'],
                    'description' => $query['description'] ?? '',
                    'required' => $query['required'] ?? false,
                    'example' => 1,
                    'schema' => [
                        'type' => gettype($query['value']),
                    ]
                ];
            }
        }

        if (!isset($this->openAPIJSON->paths[$uri])) {
            $this->openAPIJSON->paths[$uri] = new PathItem([]);
        }

        if(!isset($this->openAPIJSON->paths[$uri]->{$method})) {
            $schema = $this->getData($body, [
                'required' => $bodyRequired,
                'description' => $bodyDescription,
                'dicClass' => $info['dicClass'] ?? null,
                'context' => $info['context'] ?? '',
                'type' => 'body'
            ]);

            $this->openAPIJSON->paths[$uri]->{$method} = new \cebe\openapi\spec\Operation([
                'tags' => $tags ?? [],
                'summary' => $summary ?? '',
                'description' => $description ?? '',
                'parameters' => array_merge($params, $queries),
                'responses' => new \cebe\openapi\spec\Responses([])
            ]);

            if (!empty($body)) {
                $this->openAPIJSON->paths[$uri]->{$method}->requestBody = [
                    'content' => [
                        'application/json' => [
                            'schema' => $schema
                        ]
                    ]
                ];
            }
        }

        $schema = $this->getData($responseBody, [
            'dicClass' => $info['dicClass'] ?? null,
            'context' => $info['context'] ?? '',
            'type' => 'response'
        ]);

        if (!empty($responseBodyRequired)) {
            $schema['required'] = $responseBodyRequired;
        }

        $this->openAPIJSON->paths[$uri]->{$method}->responses->addResponse($responseStatus, new \cebe\openapi\spec\Operation([
            'description' => $responseDescription,
            'content' => [
                'application/json' => [
                    'schema' => $schema
                ]
            ]
        ]));

        foreach ($this->defaultsResponses as $defaultsResponse) {
            if (($defaultsResponse['only'] == '*' || !!preg_match('', $uri)) && (empty($defaultsResponse['without']) || !preg_match($defaultsResponse['without'], $uri))) {

                $schema = $this->getData($defaultsResponse['response']['body']);

                if (!empty($responseBodyRequired)) {
                    $schema['required'] = $responseBodyRequired;
                }

                $this->openAPIJSON->paths[$uri]->{$method}->responses->addResponse($defaultsResponse['response']['status'], new \cebe\openapi\spec\Operation([
                    'description' => $responseDescription,
                    'content' => [
                        'application/json' => [
                            'schema' => $schema
                        ]
                    ]
                ]));
            }
        }
    }

    function addRef($refName, $ref) {
        $this->refs[$refName] = $ref;
    }

    function setRefToKey($refName, $ref) {
        $this->refs[$refName] = $ref;
    }

    function getData($data, $options = []) {
        $options['required'] = \Devesharp\Support\Collection::make($options['required'] ?? [])
            ->map(function ($item) {
               return 'data.'.$item;
            })->toArray();

        $options['description'] = \Devesharp\Support\Collection::make($options['description'] ?? [])
            ->mapWithKeys(function ($value, $key) {
                return ['data.' . $key => $value];
            })->toArray();

        return $this->threeData(['data' => $data], $options)['data'];
    }

    function threeData($data, $options = [], $root = '') {
        $path = empty($root) ? $root : $root . '.';
        $dic = null;

        if (!empty($options['dicClass']) && class_exists($options['dicClass']) && isset($options['context'])) {
            $dic = app($options['dicClass']);
        }

        return \Illuminate\Support\Collection::make($data)
            ->mapWithKeys(function($value, $key) use ($path, $options, $dic) {
                if (!empty($dic)) {
                    $keyNew = str_replace('data.', '', $path . $key);
                    $value = $dic->checkKeyReplace($options['context'], $options['type'] ?? 'body', $keyNew, $value);
                }

                if (is_string($value)) {
                    $keyItem = str_replace('$', '', $value);
                    if (!empty($this->refs[$keyItem])) {
                        // Add
                        $this->refsEnabled[] = $keyItem;
                        return [
                            $key => [
                                '$ref' => '#/components/schemas/' . $keyItem
                            ]
                        ];
                    }
                }

                if (gettype($value) == 'array') {

                    $newKey = $key;
                    if ($newKey == 0) {
                        $newKey = '*';
                    }

                    if (!\Devesharp\Support\Helpers::isArrayAssoc($value)) {
                        return [
                            $key => [
                                'type' => 'array',
                                'items' => $this->threeData($value, $options, $path . $newKey)[0],
                                'example' => $value
                            ]
                        ];
                    }

                    if (empty($value)) {
                        return [
                            $key => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'example' => []
                            ]
                        ];
                    }

                    $valueKeys = [
                        'type' => 'object',
                        'properties' => $this->threeData($value, $options, $path . $newKey)
                    ];

                    foreach (array_keys($value) as $array_key) {
                        if (in_array($path . $newKey   .'.'. $array_key, $options['required'] ?? [])) {
                            $valueKeys['required'][] = $array_key;
                        }
                    }

                    return [
                        $key => $valueKeys
                    ];
                }

                $valueKeys = [
                    'type' => gettype($value),
                    'example' => $value,
                ];

                if (isset($options['description'][$path . $key])) {
                    $valueKeys['description'] = $options['description'][$path . $key];
                }

                return [
                    $key => $valueKeys
                ];
            })->toArray();
    }

    public function addDefaultResponse($response, $only = '*', $without = null) {
        $this->defaultsResponses[] = [
            'response' => $response,
            'only' => $only,
            'without' => $without,
        ];
    }

    public function toYml($file = null): string
    {
        $refs = [];
        $refsEnabled = array_values(array_unique($this->refsEnabled));
        foreach ($refsEnabled as $item) {
            $refs[$item] = $this->refs[$item];
        }

        if(!empty($refs)) {
            $this->openAPIJSON->components = [
                'schemas' => $refs
            ];
        }

        if ($file && !$this->empty) {
            file_put_contents($file, \cebe\openapi\Writer::writeToYaml($this->openAPIJSON));
        }

        return \cebe\openapi\Writer::writeToYaml($this->openAPIJSON);
    }

    public function toObject(): array
    {
        $refs = [];
        $refsEnabled = array_values(array_unique($this->refsEnabled));
        foreach ($refsEnabled as $item) {
            $refs[$item] = $this->refs[$item];
        }

        if(!empty($refs)) {
            $this->openAPIJSON->components = [
                'schemas' => $refs
            ];
        }

        return json_decode(\cebe\openapi\Writer::writeToJson($this->openAPIJSON), true);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param array $servers
     */
    public function addServers($url, $description = ''): void
    {
        $this->servers[] = [
            'url' => $url,
            'description' => $description,
        ];
    }

    public static function getInstance(): self
    {
        if(self::$instance === null){
            self::$instance = new self;
            self::$instance->init();
        }

        return self::$instance;
    }
}
