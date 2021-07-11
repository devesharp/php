<?php

namespace Tests\CRUD;

use Devesharp\CRUD\Exception;
use Devesharp\Support\Collection;
use Tests\CRUD\Mocks\DataInterfaceStub;
use Tests\CRUD\Mocks\ValidatorStub;

class DataInterfaceTest extends \Tests\TestCase
{
    protected  DataInterfaceStub $dataInterface;

    protected function setUp(): void
    {
        parent::setUp();
//        $this->dataInterface = new DataInterfaceStub();
    }

    /**
     * @testdox DataInterface - não deve adicionar valores extras
     */
    public function testValidatorStubError()
    {
        $dataInterface = new DataInterfaceStub([
            'name' => 'john',
            'age' => 20,
            'coop' => [
                'name' => 'sdsd',
                'active' => false,
            ],
        ]);

        $this->assertEquals([
            'name' => 'john',
            'age' => 20,
            'coop' => [
                'name' => 'sdsd',
//                'active' => false, // active não deve existir em DataInterfaceChildren
            ]
        ], $dataInterface->toArray());
    }

    /**
     * @testdox DataInterface - regatar valores soltos
     */
    public function testGetValueSingle()
    {
        $dataInterface = new DataInterfaceStub([
            'name' => 'john',
            'age' => 20,
            'coop' => [
                'name' => 'Coop',
                'active' => false,
            ],
        ]);

        $this->assertEquals('john', $dataInterface->name);
        $this->assertEquals('Coop', $dataInterface->coop->name);
    }

    /**
     * @testdox DataInterface - unset
     */
    public function testUnset()
    {
        $dataInterface = new DataInterfaceStub([
            'name' => 'john',
            'age' => 20,
            'coop' => [
                'name' => 'Coop',
                'active' => false,
            ],
        ]);

        unset($dataInterface->name);
        unset($dataInterface->coop->name);

        $this->assertFalse(isset($dataInterface->name));
        $this->assertFalse(isset($dataInterface->coop->name));

    }
}
