<?php

namespace Tests\CRUD\Mocks;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Illuminate\Database\Eloquent\Model;

class RepositoryStub extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = ModelStub::class;

    protected bool $disableEnabledColumn = true;

    protected $softDelete = false;
}
