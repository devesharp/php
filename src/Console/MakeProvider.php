<?php

namespace Devesharp\Console;

use Devesharp\Console\Commands\MakePolicy;
use Devesharp\Console\Commands\MakeRepository;
use Devesharp\Console\Commands\MakeAll;
use Devesharp\Console\Commands\MakeTransformer;
use Devesharp\Console\Commands\MakeValidator;
use UpInside\LaravelMakeTrait\Commands\TraitMakeCommand;
use Illuminate\Support\ServiceProvider;

class MakeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAll::class,
                MakeRepository::class,
                MakeTransformer::class,
                MakeValidator::class,
                MakePolicy::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
