<?php

namespace Devesharp\APIDocs;

use Illuminate\Support\ServiceProvider;

class APIDocsProvider extends ServiceProvider
{
    static string $file = '';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('API_DOCS_INIT')) {
            define('API_DOCS_INIT', true);
            $path = realpath(__DIR__ . '/../../config/config.php');
            $this->publishes([$path => config_path('devesharp.php')], 'config');
            $this->mergeConfigFrom($path, 'devesharp');

            $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
            $apiDocs->setTitle(config('devesharp.APIDocs.name', 'API Docs'));
            $apiDocs->setDescription(config('devesharp.APIDocs.description', ''));

            APIDocsProvider::$file = config('devesharp.APIDocs.save_file');
            file_put_contents(APIDocsProvider::$file, '');

            foreach (config('devesharp.APIDocs.servers', []) as $item) {
                if (!empty($item['url']))
                    $apiDocs->addServers($item['url'], $item['description'] ?? '');
            }
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
        if (defined('API_DOCS_ENABLED')) {
            $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
            $apiDocs->toYml(APIDocsProvider::$file);
        }
    }
}
