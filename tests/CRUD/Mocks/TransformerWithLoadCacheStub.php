<?php

namespace Tests\CRUD\Mocks;

use Devesharp\CRUD\Transformer;

class TransformerWithLoadCacheStub extends Transformer
{
    public string $model = ModelStub::class;

    protected array $loads = [
        'foo' => [RepositoryFooStub::class, 'user_create'],
    ];

    /**
     * @param $model
     * @param string $context
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function transform(
        $model,
        string $context = 'default',
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }

        $transform = $model->toArray();

        $transform['user_create'] = $this->getFoo($transform['user_create'])->login;
        $transform['updated_at'] = (string) $model->updated_at;
        $transform['created_at'] = (string) $model->created_at;

        return $transform;
    }

//    // Repository Mock
//    public function loadFoo(array $users)
//    {
//        $this->loadResource('foo', app(RepositoryFooStub::class), $users);
//    }
}
