<?php

namespace Tests\CRUD;

use Devesharp\CRUD\Exception;
use Devesharp\Support\Collection;
use Tests\CRUD\Mocks\ValidatorStub;

class ValidatorsTest extends \Tests\TestCase
{
    public ValidatorStub $validator;
    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ValidatorStub();
    }

    /**
     * @testdox Validators - validação com erro
     */
    public function testValidatorStubError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(Exception::DATA_ERROR);
        $this->expectExceptionMessage("Error on validate data:\nThe age field is required.");
        $this->validator->create(['name' => 'John']);
    }

    /**
     * @testdox Validators - validação sem erro
     */
    public function testValidatorStub()
    {
        $data = $this->validator->create(['name' => 'John', 'age' => 10, 'extends' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John', 'age' => 10]);
    }

    /**
     * @testdox Validators - removeRequiredRules deve ignorar todos os required
     */
    public function testValidatorStubRemoveRequiredRules()
    {
        $data = $this->validator->update(['name' => 'John']);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John']);
    }

    /**
     * @testdox Validators - getValidateWithSearch deve ter schema de busca padrão
     */
    public function testValidatorStubSearch()
    {
        $data = $this->validator->search([
            'query' => [
                'limit' => 1,
                'pagination' => 1,
                'offset' => 1,
                'sort' => '-id',
            ],
            'filters' => [
                'name' => 'John',
                'age' => 17,
            ]
        ]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), [
            'query' => [
                'limit' => 1,
                'pagination' => 1,
                'offset' => 1,
                'sort' => '-id',
            ],
            'filters' => [
                'name' => 'John'
            ]
        ]);
    }
}
