<?php

namespace Tests\APIDocs\Mocks;

use Devesharp\CRUD\Validator;

class CoreDocsStub
{
    protected array $required = [];

    protected array $description = [];

    public function __construct(
        protected ValidatorStub $validator
    )
    {
        $this->required = [
            'create' => $this->validator->getRequireds('create'),
            'update' => $this->validator->getRequireds('update'),
            'search' => $this->validator->getRequireds('search'),
        ];

        $this->description = [
            'create' => [
                'properties.0.id' => 'description'
            ],
        ];
    }

    public function convertBody($body, $method = 'create') {
        $bodyDot = \Illuminate\Support\Arr::dot($body);
        $data = [];

        foreach ($bodyDot as $key => $value) {
            if (isset($this->description[$method][$key])) {
              \Illuminate\Support\Arr::set($data, $key, $value);
            } else {
                \Illuminate\Support\Arr::set($data, $key, $value);
            }
        }

        return $data;
    }

    public function getDescription($key): array
    {
        return $this->description[$key];
    }
}
