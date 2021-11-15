<?php

namespace Tests\APIDocs\Mocks;

use Devesharp\CRUD\Validator;

class ValidatorStub extends Validator
{
    protected array $rules = [
        'create' => [
            'name' => 'string|max:100|required',
            'age' => 'numeric|required',
            'active' => 'boolean',
            'properties' => 'array',
            'properties.*.id' => 'numeric',
            'item' => 'object',
            'item.id' => 'numeric',
        ],
        'update' => [
            '_extends' => 'create',
            'id' => 'numeric',
        ],
        // Busca
        'search' => [
            'filters.name' => 'string',
            'filters.full_name' => 'string',
        ],
    ];


    public function create(array $data, $requester = null)
    {
        $context = 'create';

        return $this->validate($data, $this->getValidate($context));
    }

    public function update(array $data, $requester = null)
    {
        $context = 'update';

        return $this->validate($data, $this->removeRequiredRules($this->getValidate($context)));
    }

    public function search(array $data, $requester = null)
    {
        return $this->validate($data, $this->getValidateWithSearch('search'));
    }
}
