<?php

namespace Leadobo\IntegrationTemplate;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{

    // php artisan vendor:publish --tag=randomable-seeds
    // php artisan db:seed --class=RandomableTableSeeder

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* FROM NOVA
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova');
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova');

            if (Nova::runsMigrations()) {
                $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            }

            $this->registerRoutes();
        */


        /*
         * Event::listen(
        PodcastProcessed::class,
        [SendPodcastNotification::class, 'handle']
    );
         */
        if ($this->app->runningInConsole()) {
            // $this->publishResources();
        }

        $this->app->booted(function () {
            $this->migrations();
            $this->routes();
            $this->validators();
        });
    }

    /**
     * Run the Integration migrations.
     *
     * @return void
     */
    protected function migrations()
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register the Integration routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::prefix('/leadobo/integrations/facebook')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any Integration validators.
     *
     * @return void
     */
    protected function validators()
    {
        Validator::extend('verify_something', function($attribute, $value, $parameters) {
            return false;
        });
    }

    /**
     * Register any Integration events.
     *
     * @return void
     */
    protected function events()
    {

    }

    /**
     * Register any Integration services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
