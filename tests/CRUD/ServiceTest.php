<?php

namespace Tests\CRUD;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tests\CRUD\Mocks\ModelRepositoryStub;
use Tests\CRUD\Mocks\ModelStub;
use Tests\CRUD\Mocks\ServiceStub;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    public ServiceStub $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ServiceStub::class);
    }

    /**
     * @testdox Service - create
     */
    public function testCreateService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        $this->assertEquals(Helpers::arrayExclude($model, ['created_at', 'updated_at']), [
            'id' => 1,
            'name' => 'John',
            'age' => 10
        ]);
    }

    /**
     * @testdox Service - update
     */
    public function testUpdateService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        $model = $this->service->update($model['id'], [
            'name' => 'John Wick',
            'age' => 11,
            'extends' => true
        ]);

        $this->assertEquals(Helpers::arrayExclude($model, ['created_at', 'updated_at']), [
            'id' => 1,
            'name' => 'John Wick',
            'age' => 11
        ]);
    }

    /**
     * @testdox Service - search
     */
    public function testSearchService()
    {
        $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Veronica',
            'age' => 12,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Woo Lipters',
            'age' => 80,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Willy John',
            'age' => 21,
            'extends' => true
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->search([
            'filters' => [
                'name' => 'john'
            ]
        ]);
        $this->assertEquals(Arr::pluck($result['results'], 'id'), [1, 4]);
        $this->assertEquals($result['count'], 2);

        /**
         * Buscar full_name raw
         */
        $result = $this->service->search([
            'filters' => [
                'full_name' => 'john 21'
            ]
        ]);

        $this->assertEquals(Arr::pluck($result['results'], 'id'), [4]);
        $this->assertEquals($result['count'], 1);
    }

    /**
     * @testdox Service - delete
     */
    public function testDeleteService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->delete($model['id']);

        $this->assertEquals(true, $result);

        $this->assertEquals(null, ModelStub::query()->first());
    }
}
