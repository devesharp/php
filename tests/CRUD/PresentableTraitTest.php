<?php

namespace Tests\CRUD;

use Devesharp\CRUD\Presenter\Exceptions\PresenterException;
use Devesharp\CRUD\Presenter\PresentableTrait;
use Devesharp\CRUD\Presenter\Presenter;
use Illuminate\Database\Eloquent\Model;

class PresentableTraitTest extends \Tests\TestCase
{
    /**
     * @var ModelExample
     */
    protected $model;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->model = new ModelExample();
    }

//    /**
//     * @test
//     */
//    public function exceptionIfPresenterPropertyNotExists()
//    {
//        $this->expectException(PresenterException::class);
//
//        $this->getMockObjectGenerator()->getObjectForTrait(PresentableTrait::class)->present();
//    }

    /**
     * @test
     */
    public function returnValidPresenterClass()
    {
        $this->assertInstanceOf(Presenter::class, $this->model->present());
    }

    /**
     * @test
     */
    public function cachePresenterInstanceIsWorking()
    {
        $call1 = $this->model->present();
        $call2 = $this->model->present();

        $this->assertSame($call1, $call2);
        $this->assertNotSame($call1, (new ModelExample())->present());
    }
}

class ModelExample extends Model
{
    use PresentableTrait;

    protected $presenter = ModelPresenter::class;
}

class ModelPresenter extends Presenter
{
    //
}
