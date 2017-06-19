<?php

namespace Deseco\Economic;

use Illuminate\Support\ServiceProvider;

class EconomicServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/economic.php';

        $this->publishes([$configPath => config_path('economic.php')], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['economic'] = $this->app->share(function ($app) {

            $config = $app['config']->get('economic');

            return (new Economic())
                ->setConfig($config)
                ->setAppToken($config['appToken'])
                ->setGrantToken($config['grantToken'])
                ->connect();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['economic'];
    }
}
