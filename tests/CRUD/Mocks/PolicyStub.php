<?php

namespace Tests\CRUD\Mocks;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Illuminate\Database\Eloquent\Model;

class PolicyStub
{
    function create($request) {
        // \Devesharp\CRUD\Exception::Unauthorized();
    }

    function update($request, $model) {
        // \Devesharp\CRUD\Exception::Unauthorized();
    }

    function get($request, $model) {
        // \Devesharp\CRUD\Exception::Unauthorized();
    }

    function search($request) {
        // \Devesharp\CRUD\Exception::Unauthorized();
    }

    function delete($request, $model) {
        // \Devesharp\CRUD\Exception::Unauthorized();
    }
}
