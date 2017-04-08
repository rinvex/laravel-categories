<?php

declare(strict_types=1);

namespace Rinvex\Categorizable;

use Illuminate\Support\ServiceProvider;

class CategorizableServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/config.php'), 'rinvex.category');
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Load migrations
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            // Publish Resources
            $this->publishResources();
        }
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        // Publish config
        $this->publishes([
            realpath(__DIR__.'/../config/config.php') => config_path('rinvex.category.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            realpath(__DIR__.'/../database/migrations') => database_path('migrations'),
        ], 'migrations');
    }
}
