<?php

namespace Devesharp;

class RepositoryMysql extends RepositoryInterface
{
    protected $noEnabled = false;

    protected $softDelete = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $modelQuery = null;

    /**
     * @var string
     */
    public $tableName = null;

    public function __construct()
    {
        $this->modelQuery = new $this->model();
        $this->tableName = $this->modelQuery->getTable();
        $this->modelQuery = $this->modelQuery->query();
    }

    public function create($body)
    {
        return $this->model::create($body);
    }

    public function createMany($values)
    {
        return (new $this->model())->newQuery()->insert($values);
    }

    /**
     * Resgatar recurso por ID.
     *
     * @param $id
     * @param bool $enabled
     *
     * @return null
     */
    public function findById($id, $enabled = true)
    {
        $this->clearQuery();
        $model = $this->modelQuery
            ->where((new $this->model())->getTable() . '.id', intval($id))
            ->limit(1)
            ->first();

        if (
            ! empty($model) &&
            $enabled &&
            ! $model->enabled &&
            ! $this->noEnabled
        ) {
            return null;
        }

        return $model;
    }

    /**
     * Resgatar recurso por ID ou falhar.
     *
     * @param $id
     * @param bool $enabled
     *
     * @throws \App\Handlers\Exception
     *
     * @return null
     */
    public function findIdOrFail($id, $enabled = true)
    {
        $model = $this->findById($id, $enabled);

        if (empty($model)) {
            \App\Handlers\Exception::NotFound();
        }

        return $model;
    }

    public function findOne($enabled = true)
    {
        if ($enabled && ! $this->noEnabled) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $model = $this->modelQuery->limit(1)->first();

        $this->clearQuery();

        if (
            ! empty($model) &&
            $enabled &&
            ! $model->enabled &&
            ! $this->noEnabled
        ) {
            return null;
        }

        return $model;
    }

    public function findMany($enabled = true)
    {
        if ($enabled && ! $this->noEnabled) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $query = $this->modelQuery->get()->all();

        $this->clearQuery();

        return $query;
    }

    public function updateById($id, $body)
    {
        $model = $this->model::find($id);

        return $model->update($body);
    }

    public function update($body)
    {
        $model = $this->modelQuery->update($body);

        $this->clearQuery();

        return $model;
    }

    public function updateOne($body)
    {
        $query = $this->modelQuery->limit(1)->update($body);

        $this->clearQuery();

        return $query;
    }

    public function chunk(int $count, \Closure $callback)
    {
        $this->modelQuery->chunk($count, $callback);
        $this->clearQuery();
    }

    public function delete()
    {
        if ($this->softDelete) {
            $model = $this->update(['enabled' => 0]);
        } else {
            $model = $this->modelQuery->delete();
        }

        $this->clearQuery();

        return $model;
    }

    public function deleteById($id, $auth = null)
    {
        $model = new $this->model();
        $model = $model->where('id', $id);

        if ($this->softDelete) {
            if ($auth) {
                $model->update([
                    'enabled' => 0,
                    'deleted_at' => now(),
                    'deleted_by' => $auth->id,
                ]);
            }

            return $model->update(['enabled' => 0]);
        } else {
            return $model->delete();
        }
    }

    public function clearQuery()
    {
        $this->modelQuery = clone (new $this->model())->query();

        return $this;
    }

    public function whereRaw($column)
    {
        $this->modelQuery = $this->modelQuery->whereYear($column);

        return $this;
    }

    public function whereNull($column)
    {
        $this->modelQuery = $this->modelQuery->whereNull($column);

        return $this;
    }

    public function whereNotNull($column)
    {
        $this->modelQuery = $this->modelQuery->whereNotNull($column);

        return $this;
    }

    public function whereBoolean($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, $value ? 1 : 0);

        return $this;
    }

    public function whereDate($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereDateGt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, '>', $value);

        return $this;
    }

    public function whereDateGte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, '>=', $value);

        return $this;
    }

    public function whereDateLt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, '<', $value);

        return $this;
    }

    public function whereDateLte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, '<=', $value);

        return $this;
    }

    public function whereInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, intval($value));

        return $this;
    }

    public function whereIntGt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function whereIntGte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function whereIntLt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function whereIntLte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function whereNotInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function whereSameString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where($column, 'LIKE', $value);

        return $this;
    }

    public function whereNotLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function whereBeginWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function whereEndWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function whereContainsLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function whereContainsExplodeString($column, $value)
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->where($column, 'LIKE', $value);

        return $this;
    }

    public function whereArrayInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->whereIn($column, $value);

        return $this;
    }

    public function whereArrayNotInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->whereNotIn($column, $value);

        return $this;
    }

    public function whereArrayString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->whereIn($column, $value);

        return $this;
    }

    public function whereArrayNotString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->whereNotIn($column, $value);

        return $this;
    }

    public function orWhereBoolean($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value ? 1 : 0);

        return $this;
    }

    public function orWhereInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, intval($value));

        return $this;
    }

    public function orWhereNotInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntGt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntGte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntLt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntLte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function orWhereSameString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value);

        return $this;
    }

    public function orWhereLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, 'LIKE', $value);

        return $this;
    }

    public function orWhereNotLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function orWhereBeginWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function orWhereEndWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function orWhereContainsLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function orWhereContainsExplodeString($column, $value)
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->orWhere($column, 'LIKE', $value);

        return $this;
    }

    public function orWhereArrayInt($column, $value)
    {
        if (! is_array($value)) {
            $this->modelQuery = $this->modelQuery->orWhereRaw(
                'FIND_IN_SET(?,' . $column . ')',
                [$value],
            );
        } else {
            $value = array_map('intVal', $value);
            $this->modelQuery = $this->modelQuery->orWhereIn($column, $value);
        }

        return $this;
    }

    public function orWhereArrayNotInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->orWhereNotIn($column, $value);

        return $this;
    }

    public function orWhereArrayString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhereIn($column, $value);

        return $this;
    }

    public function orWhereArrayNotString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orWhereNotIn($column, $value);

        return $this;
    }

    public function orWhere($callback)
    {
        $that = $this;
        $this->modelQuery = $this->modelQuery->orWhere(function ($model) use (
            $callback,
            $that
        ) {
            $this->modelQuery = $model;
            $callback($that);
        });

        return $this;
    }

    public function andWhere($callback)
    {
        $that = $this;
        $this->modelQuery = $this->modelQuery->where(function ($model) use (
            $callback,
            $that
        ) {
            $this->modelQuery = $model;
            $callback($that);
        });

        return $this;
    }

    public function havingNull($column)
    {
        $this->modelQuery = $this->modelQuery->havingRaw($column . ' IS NULL');

        return $this;
    }

    public function havingNotNull($column)
    {
        $this->modelQuery = $this->modelQuery->havingRaw($column . ' IS NOT NULL');

        return $this;
    }

    public function havingBoolean($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having($column, $value ? 1 : 0);

        return $this;
    }

    public function havingInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having($column, intval($value));

        return $this;
    }

    public function havingIntGt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function havingIntGte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function havingIntLt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function havingIntLte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function havingSameString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }

    public function havingLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having($column, 'LIKE', $value);

        return $this;
    }

    public function havingNotLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function havingBeginWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function havingEndWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function havingContainsLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function havingContainsExplodeString($column, $value)
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';

        $this->modelQuery = $this->modelQuery->having($column, 'LIKE', $value);

        return $this;
    }

    public function havingArrayInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayNotInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayString($column, $value)
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayNotString($column, $value)
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function orHavingBoolean($column, $value)
    {
        $this->modelQuery = $this->modelQuery->having($column, $value ? 1 : 0);

        return $this;
    }

    public function orHavingInt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            intval($value),
        );

        return $this;
    }

    public function orHavingIntGt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntGte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntLt($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntLte($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function orHavingSameString($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, $value);

        return $this;
    }

    public function orHavingLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingNotLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingBeginWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function orHavingEndWithLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function orHavingContainsLike($column, $value)
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function orHavingContainsExplodeString($column, $value)
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingArrayInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayNotInt($column, $value)
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayString($column, $value)
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayNotString($column, $value)
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '`' . $column . '` not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orderBy($column, $order)
    {
        if ('asc' === $order) {
            $this->modelQuery = $this->modelQuery->orderBy($column, $order);
        } elseif ('desc' === $order) {
            $this->modelQuery = $this->modelQuery->orderBy($column, $order);
        }

        return $this;
    }

    public function orderByRaw($column)
    {
        $this->modelQuery = $this->modelQuery->orderByRaw($column);

        return $this;
    }

    public function limit($limit = null)
    {
        if (! empty($limit)) {
            $this->modelQuery = $this->modelQuery->limit($limit);
        }

        return $this;
    }

    public function offset($offset = null)
    {
        $this->modelQuery = $this->modelQuery->offset($offset);

        return $this;
    }

    public function count($enabled = true)
    {
        if ($enabled && ! $this->noEnabled) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        return $this->modelQuery->count();
    }

    public function cursor($enabled = true)
    {
        if ($enabled && ! $this->noEnabled) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        return $this->modelQuery->cursor();
    }

    public function increment(string $column, $amount = 1, $enabled = true)
    {
        if ($enabled && ! $this->noEnabled) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $increment = $this->modelQuery->increment($column, $amount);

        $this->clearQuery();

        return $increment;
    }

    public function getBuilder()
    {
        return $this->modelQuery;
    }

    public function __clone()
    {
        $this->modelQuery = clone $this->modelQuery;
    }
}
