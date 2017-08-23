<?php

declare(strict_types=1);

namespace Rinvex\Categorizable\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Categorizable\Contracts\CategoryContract;
use Rinvex\Categorizable\Console\Commands\MigrateCommand;

class CategorizableServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.categorizable.migrate',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.categorizable');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.categorizable.category', function ($app) {
            return new $app['config']['rinvex.categorizable.models.category']();
        });
        $this->app->alias('rinvex.categorizable.category', CategoryContract::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Load migrations
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.categorizable.php')], 'rinvex-categorizable-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-categorizable-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }
}
