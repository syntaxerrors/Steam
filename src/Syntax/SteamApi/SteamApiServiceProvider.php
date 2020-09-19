<?php

namespace Syntax\SteamApi;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SteamApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('steam-api.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAlias();

        $this->container->singleton('steam-api', function () {
            return new Client;
        });
    }

    /**
     * Register the alias for package.
     *
     * @return void
     */
    protected function registerAlias()
    {
        $this->container->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Steam', 'Syntax\SteamApi\Facades\SteamApi');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['steam-api'];
    }
}
