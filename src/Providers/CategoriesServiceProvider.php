<?php

declare(strict_types=1);

namespace Rinvex\Categories\Providers;

use Rinvex\Categories\Models\Category;
use Illuminate\Support\ServiceProvider;
use Rinvex\Categories\Console\Commands\MigrateCommand;
use Rinvex\Categories\Console\Commands\PublishCommand;
use Rinvex\Categories\Console\Commands\RollbackCommand;

class CategoriesServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.categories.migrate',
        PublishCommand::class => 'command.rinvex.categories.publish',
        RollbackCommand::class => 'command.rinvex.categories.rollback',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.categories');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.categories.category', $categoryModel = $this->app['config']['rinvex.categories.models.category']);
        $categoryModel === Category::class || $this->app->alias('rinvex.categories.category', Category::class);

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
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.categories.php')], 'rinvex-categories-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-categories-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}
