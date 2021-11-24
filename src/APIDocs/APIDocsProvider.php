<?php

namespace Devesharp\APIDocs;

use Devesharp\Console\Commands\MakeController;
use Devesharp\Console\Commands\MakeFactoryService;
use Devesharp\Console\Commands\MakeModel;
use Devesharp\Console\Commands\MakePolicy;
use Devesharp\Console\Commands\MakePresenter;
use Devesharp\Console\Commands\MakeRepository;
use Devesharp\Console\Commands\MakeAll;
use Devesharp\Console\Commands\MakeRoute;
use Devesharp\Console\Commands\MakeRouteTestService;
use Devesharp\Console\Commands\MakeService;
use Devesharp\Console\Commands\MakeTransformer;
use Devesharp\Console\Commands\MakeUnitTestService;
use Devesharp\Console\Commands\MakeValidator;
use Devesharp\Console\Commands\MakeDictionary;
use UpInside\LaravelMakeTrait\Commands\TraitMakeCommand;
use Illuminate\Support\ServiceProvider;

class APIDocsProvider extends ServiceProvider
{
    protected string $file = '';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/config.php');
        $this->publishes([$path => config_path('devesharp.php')], 'config');
        $this->mergeConfigFrom($path, 'devesharp');

        $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
        $apiDocs->setTitle(config('devesharp.APIDocs.name', 'API Docs'));
        $apiDocs->setDescription(config('devesharp.APIDocs.description', ''));

        $this->file = config('devesharp.APIDocs.save_file');

        foreach (config('devesharp.APIDocs.servers', []) as $item) {
            if (!empty($item['url']))
                $apiDocs->addServers($item['url'], $item['description'] ?? '');
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

    public function __destruct()
    {
        $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
        $apiDocs->toYml($this->file);
    }
}
