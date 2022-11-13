<?php

namespace BrandonJBegle\SlackChannel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config' => config_path(),
            ], 'slack-channel-nova-field');
        }

        Nova::serving(function (ServingNova $event) {
            Nova::script('slack-channel-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('slack-channel-field', __DIR__ . '/../dist/css/field.css');
        });

        $this->app->booted(function () {
            if ($this->app->routesAreCached()) {
                return;
            }
            Route::middleware(['nova'])
                ->prefix('nova-vendor/slack-channel-field')
                ->group(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/slack-channel-nova-field.php', 'slack-channel-nova-field');
    }
}
