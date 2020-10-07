<?php

namespace Devesharp;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Str;

class CRUD
{
    /**
     * Sorts permitidas.
     *
     * @var array
     */
    public $sort = [];

    /**
     * Sort padrão.
     *
     * @var string
     */
    public $sort_default = '';

    /**
     * Filtros.
     *
     * @var array
     */
    public $filters = [];

    /**
     * Filtros permitidos.
     *
     * @var array
     */
    public $filters_allowed = [];

    /**
     * @var int
     */
    public $limitMax = 20;

    /**
     * @param  $data
     * @param  null                $auth
     * @return RepositoryInterface
     */
    protected function makeSearch(&$data, $auth = null)
    {
        if ($data instanceof Collection) {
            return $this->filterSearch(
                $data->toArray(),
                $this->getQuerySearch($data->toArray()),
            );
        } else {
            return $this->filterSearch($data, $this->getQuerySearch($data));
        }
    }

    /**
     * @param  RepositoryInterface $repository
     * @param  mixed               $transformer
     * @param  mixed               $context
     * @param  mixed|null          $auth
     * @return array
     */
    protected function transformerSearch(
        $repository,
        $transformer,
        $context,
        $auth = null
    ) {
        return [
            'results' => Transformer::collection(
                (clone $repository)->findMany(),
                $transformer,
                $context,
                $auth,
            ),
            'count' => (clone $repository)
                ->limit(PHP_INT_MAX)
                ->offset(0)
                ->count(),
        ];
    }

    /**
     * @param $body
     * @param RepositoryInterface $repository
     *
     * @return RepositoryInterface
     */
    public function filterSearch($body, $repository)
    {
        $limit = $this->limitMax;

        /*
         * Limit
         */
        if (isset($body['query']['limit'])) {
            $limit = intval($body['query']['limit']);

            if ($limit > $this->limitMax) {
                $repository->limit($this->limitMax);
            } else {
                $repository->limit($limit);
            }
        } else {
            $repository->limit(20);
        }

        /*
         * Paginacão
         */
        if (isset($body['query']['page'])) {
            $page = intval($body['query']['page']);
            $page = $page < 1 ? 1 : $page;
            --$page;

            $repository->offset($page * $limit);
        }

        /*
         * Sort
         */
        $query['sort'] = $body['query']['sort'] ?? $this->sort_default;

        if (! empty($query['sort'])) {
            foreach (explode(',', $query['sort']) as $key => $value) {
                //DESC
                if ('-' == $value[0]) {
                    if (in_array(substr($value, 1), $this->sort)) {
                        $repository->orderBy(substr($value, 1), 'desc');
                    } else {
                        foreach ($this->sort as $sort_key => $sort_value) {
                            if (
                                is_array($sort_value) &&
                                $sort_key == substr($value, 1)
                            ) {
                                $repository->orderBy(
                                    $sort_value['column'],
                                    'desc',
                                );
                            }
                        }
                    }
                } else {
                    if (in_array($value, $this->sort)) {
                        $repository->orderBy($value, 'asc');
                    } else {
                        foreach ($this->sort as $sort_key => $sort_value) {
                            if (
                                (is_array($sort_value) &&
                                    $sort_key == $value) ||
                                (is_string($sort_value) &&
                                    $sort_value == $value)
                            ) {
                                $repository->orderBy(
                                    $sort_value['column'],
                                    'asc',
                                );
                            }
                        }
                    }
                }
            }
        }

        /*
         * Filtrar
         */
        if (isset($body['filters'])) {
            foreach ($body['filters'] as $key => $value) {
                if (isset($this->filters[$key])) {
                    $filter = $this->filters[$key];
                    $functionName = ucfirst(
                        \Illuminate\Support\Str::camel($filter['filter']),
                    );

                    if (Str::contains($filter['column'], 'Searchable')) {
                        $value = searchable_string($value);
                    }

                    if (Str::contains($filter['column'], 'raw:')) {
                        $filter['column'] = Manager::raw(
                            str_replace('raw:', '', $filter['column']),
                        );
                    }

                    if (
                        empty($filter['clause']) ||
                        'where' == $filter['clause']
                    ) {
                        $repository->{'where' . $functionName}(
                            $filter['column'],
                            $value,
                        );
                    } else {
                        $repository->{'having' . $functionName}(
                            $filter['column'],
                            $value,
                        );
                    }
                }
            }
        }

        return $repository;
    }

    /**
     * Retorna repositorio com recursos a serem resolvidos na acao
     * Deletar, favoritar, destacar, etc..
     *
     * @param  $target
     * @param  null                $auth
     * @throws Exception
     * @return RepositoryInterface
     */
    public function actionResource($target, $auth = null)
    {
        if (empty($target)) {
            Exception::NotFound();
        }

        /*
         * Verifica se é ID unico, Array de Ids ou um determinado filtro
         */
        if (is_numeric($target)) {
            $query = $this->makeSearch($data, $auth);
            if ($query instanceof Repository) {
                $query->whereInt($query->tableName . '.id', $target);
            } else {
                $query->whereInt('id', $target);
            }
        } elseif (is_array($target) && ! is_array_assoc($target)) {
            $query = $this->makeSearch($data, $auth);

            // Deve ser array numerica
            if (! is_numeric_array($target) && ! is_numeric_string($target)) {
                Exception::Exception(Exception::DATA_ERROR_GENERAL);
            }

            if ($query instanceof Repository) {
                $query->whereArrayInt($query->tableName . '.id', $target);
            } else {
                $query->whereArrayInt('id', $target);
            }
        } elseif (is_object($target) || is_array_assoc($target)) {
            $target = ['filters' => $target];

            if (
                isset($this->validator) &&
                method_exists($this->validator, 'search')
            ) {
                $target = $this->validator->search($target, $auth);
            }

            $query = $this->makeSearch($target, $auth);
        } else {
            Exception::Exception(Exception::DATA_ERROR_GENERAL);
        }

        if (0 === (clone $query)->count()) {
            Exception::NotFound();
        }

        return $query;
    }

    /**
     * @param  array|null $body
     * @return Repository
     */
    public function getQuerySearch(array $body = null)
    {
        return (clone $this->repository)->clearQuery();
    }
}
