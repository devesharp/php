<?php

namespace Devesharp\CRUD;

use Devesharp\CRUD\Repository\RepositoryInterface;
use Devesharp\Support\Helpers;

class Transformer
{
    /**
     * @param $model
     * @param string $context
     * @param null $requester
     * @return mixed
     */
    public function transform(
        $model,
        string $context = 'default',
        $requester = null
    ) {
        return $model;
    }

    /**
     * @param  array  $models
     * @param  string $context
     * @param  null   $requester
     * @return array
     */
    public function transformMany(
        array $models,
        string $context = 'default',
        $requester = null
    ) {
        $transformed = [];

        if (Helpers::isArrayAssoc($models)) {
            foreach ($models as $key => $model) {
                $transformed[$key] = $this->transform(
                    $model,
                    $context,
                    $requester,
                );
            }
        } else {
            foreach ($models as $model) {
                $transformed[] = $this->transform($model, $context, $requester);
            }
        }

        return $transformed;
    }

    /**
     * Carregar recursos e colocar em cache.
     *
     * @param string              $name
     * @param RepositoryInterface $repository
     * @param array               $items
     * @param string              $column
     */
    public function loadResource(
        string $name,
        RepositoryInterface $repository,
        array $items,
        $column = 'id'
    ) {
        if (! isset($this->{$name})) {
            $this->{$name} = [];
        }

        $idsNotLoad = [];

        foreach ($items as $id) {
            if (! isset($this->{$name}[$id])) {
                $idsNotLoad[] = $id;
            }
        }

        if (empty($idsNotLoad)) {
            return;
        }

        $items = $repository
            ->clearQuery()
            ->whereArrayInt($repository->tableName . '.' . $column, $idsNotLoad)
            ->findMany();

        foreach ($items as $item) {
            $this->{$name}[$item->{$column}] = $item;
        }
    }

    /**
     * Verifica se existe uma variavel com o nome do recurso
     * Se existir verifica pelo ID se o recurso já foi resgatado
     * caso não tenha sido resgatado chama load{resource}, carrega e salva em cache.
     *
     * @param  $funName
     * @param  $arguments
     * @return mixed
     */
    public function __call($funName, $arguments)
    {
        // Verifica função
        if (0 === strpos($funName, 'get')) {
            $name = str_replace('get', '', $funName);

            if (isset($this->{lcfirst($name)}[$arguments[0]])) {
                return $this->{lcfirst($name)}[$arguments[0]];
            } else {
                $this->{lcfirst($name)} = [];
            }

            // Carregar recursp
            $item = $this->{'load' . $name}([$arguments[0]]);

            return $this->{lcfirst($name)}[$arguments[0]];
        }
    }

    /**
     * @param $model
     * @param Transformer $transform
     * @param string      $context
     * @param mixed|null  $requester
     *
     * @return mixed
     */
    public static function item(
        $model,
        Transformer $transform,
        string $context = 'default',
        $requester = null
    ) {
        return $transform->transform($model, $context, $requester);
    }

    /**
     * @param $models
     * @param Transformer $transform
     * @param string      $context
     * @param mixed|null  $requester
     *
     * @return array
     */
    public static function collection(
        $models,
        Transformer $transform,
        string $context = 'default',
        $requester = null
    ) {
        return $transform->transformMany($models, $context, $requester);
    }
}
