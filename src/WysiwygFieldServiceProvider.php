<?php

namespace Michielkempen\NovaWysiwygField;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Michielkempen\NovaWysiwygField\Jobs\PruneStaleWysiwygFiles;

class WysiwygFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('wysiwyg-field', __DIR__.'/../dist/js/field.js');
            Nova::style('wysiwyg-field', __DIR__.'/../dist/css/field.css');
        });

        if (! class_exists('CreateWysiwygFieldTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_wysiwyg_field_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_wysiwyg_field_tables.php",
            ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../dist/fonts/' => public_path('vendor/nova/fonts')
        ], 'fonts');

        $this->app->booted(function () {
            $this->loadRoutes();
            $this->schedulePruning();
        });
    }

    /**
     * Register the field's routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', 'api'])
            ->prefix('nova-vendor/michielkempen/nova-wysiwyg-field')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register the pruning command to run daily.
     *
     * @return void
     */
    protected function schedulePruning()
    {
        $schedule = $this->app->make(Schedule::class);
        $schedule->call(new PruneStaleWysiwygFiles)->daily();
    }
}
