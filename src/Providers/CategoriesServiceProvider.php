<?php

declare(strict_types=1);

namespace Rinvex\Categories\Providers;

use Rinvex\Categories\Models\Category;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
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
        $this->commands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register paths to be published by the publish command.
        $this->publishConfigFrom(__DIR__.'/../../config/config.php', 'rinvex/categories');
        $this->publishMigrationsFrom(__DIR__.'/../../database/migrations', 'rinvex/categories');

        ! $this->app['config']['rinvex.categories.autoload_migrations'] || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Map relations
        Relation::morphMap([
            'category' => config('rinvex.categories.models.category'),
        ]);
    }
}
