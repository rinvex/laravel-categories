<?php

declare(strict_types=1);

namespace Rinvex\Categories\Providers;

use Rinvex\Categories\Models\Category;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Categories\Console\Commands\MigrateCommand;
use Rinvex\Categories\Console\Commands\PublishCommand;
use Rinvex\Categories\Console\Commands\RollbackCommand;

class CategoriesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

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
        $this->registerModels([
            'rinvex.categories.category' => Category::class,
        ]);

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-categories');
        $this->publishesMigrations('rinvex/laravel-categories');
        ! $this->autoloadMigrations('rinvex/laravel-categories') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
