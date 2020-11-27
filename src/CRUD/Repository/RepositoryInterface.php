<?php

namespace Devesharp\CRUD\Repository;

abstract class RepositoryInterface
{
    protected $softDelete = true;

    protected $model = null;

    protected $modelQuery = null;

    public $tableName = null;

    abstract public function create($body);

    abstract public function findById($id, $enabled = true);

    abstract public function findOne($enabled = true);

    abstract public function findMany($enabled = true);

    abstract public function updateById($id, $body);

    abstract public function update($body);

    abstract public function updateOne($body);

    abstract public function chunk(int $count, \Closure $callback);

    abstract public function delete();

    abstract public function deleteById($id);

    abstract public function whereNull($column);

    abstract public function whereNotNull($column);

    abstract public function whereBoolean($column, $value);

    abstract public function whereInt($column, $value);

    abstract public function whereIntGt($column, $value);

    abstract public function whereIntGte($column, $value);

    abstract public function whereIntLt($column, $value);

    abstract public function whereIntLte($column, $value);

    abstract public function whereNotInt($column, $value);

    abstract public function whereSameString($column, $value);

    abstract public function whereLike($column, $value);

    abstract public function whereNotLike($column, $value);

    abstract public function whereBeginWithLike($column, $value);

    abstract public function whereEndWithLike($column, $value);

    abstract public function whereContainsLike($column, $value);

    abstract public function whereContainsExplodeString($column, $value);

    abstract public function whereArrayInt($column, $value);

    abstract public function whereArrayNotInt($column, $value);

    abstract public function whereArrayString($column, $value);

    abstract public function whereArrayNotString($column, $value);

    abstract public function orWhereBoolean($column, $value);

    abstract public function orWhereInt($column, $value);

    abstract public function orWhereNotInt($column, $value);

    abstract public function orWhereIntGt($column, $value);

    abstract public function orWhereIntGte($column, $value);

    abstract public function orWhereIntLt($column, $value);

    abstract public function orWhereIntLte($column, $value);

    abstract public function orWhereSameString($column, $value);

    abstract public function orWhereLike($column, $value);

    abstract public function orWhereNotLike($column, $value);

    abstract public function orWhereBeginWithLike($column, $value);

    abstract public function orWhereEndWithLike($column, $value);

    abstract public function orWhereContainsLike($column, $value);

    abstract public function orWhereContainsExplodeString($column, $value);

    abstract public function orWhereArrayInt($column, $value);

    abstract public function orWhereArrayNotInt($column, $value);

    abstract public function orWhereArrayString($column, $value);

    abstract public function orWhereArrayNotString($column, $value);

    abstract public function orWhere($callback);

    abstract public function andWhere($callback);

    abstract public function orderBy($column, $order);

    abstract public function orderByRaw($column);

    abstract public function limit($limit = null);

    abstract public function offset($offset = null);

    abstract public function count($enabled = true);

    abstract public function cursor($enabled = true);

    abstract public function getBuilder();
}
