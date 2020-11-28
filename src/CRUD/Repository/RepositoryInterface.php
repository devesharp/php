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

    abstract public function whereNull($column): self;

    abstract public function whereNotNull($column): self;

    abstract public function whereBoolean($column, $value): self;

    abstract public function whereEqual($column, $value): self;

    abstract public function whereInt($column, $value): self;

    abstract public function whereIntGt($column, $value): self;

    abstract public function whereIntGte($column, $value): self;

    abstract public function whereIntLt($column, $value): self;

    abstract public function whereIntLte($column, $value): self;

    abstract public function whereNotInt($column, $value): self;

    abstract public function whereSameString($column, $value): self;

    abstract public function whereLike($column, $value): self;

    abstract public function whereNotLike($column, $value): self;

    abstract public function whereBeginWithLike($column, $value): self;

    abstract public function whereEndWithLike($column, $value): self;

    abstract public function whereContainsLike($column, $value): self;

    abstract public function whereContainsExplodeString($column, $value): self;

    abstract public function whereArrayInt($column, $value): self;

    abstract public function whereArrayNotInt($column, $value): self;

    abstract public function whereArrayString($column, $value): self;

    abstract public function whereArrayNotString($column, $value): self;

    abstract public function orWhereBoolean($column, $value): self;

    abstract public function orWhereInt($column, $value): self;

    abstract public function orWhereNotInt($column, $value): self;

    abstract public function orWhereIntGt($column, $value): self;

    abstract public function orWhereIntGte($column, $value): self;

    abstract public function orWhereIntLt($column, $value): self;

    abstract public function orWhereIntLte($column, $value): self;

    abstract public function orWhereSameString($column, $value): self;

    abstract public function orWhereLike($column, $value): self;

    abstract public function orWhereNotLike($column, $value): self;

    abstract public function orWhereBeginWithLike($column, $value): self;

    abstract public function orWhereEndWithLike($column, $value): self;

    abstract public function orWhereContainsLike($column, $value): self;

    abstract public function orWhereContainsExplodeString($column, $value): self;

    abstract public function orWhereArrayInt($column, $value): self;

    abstract public function orWhereArrayNotInt($column, $value): self;

    abstract public function orWhereArrayString($column, $value): self;

    abstract public function orWhereArrayNotString($column, $value): self;

    abstract public function orWhere($callback): self;

    abstract public function andWhere($callback): self;

    abstract public function orderBy($column, $order): self;

    abstract public function orderByRaw($column): self;

    abstract public function limit($limit = null): self;

    abstract public function offset($offset = null): self;

    abstract public function count($enabled = true): int;

    abstract public function cursor($enabled = true);

    abstract public function getBuilder();
}
