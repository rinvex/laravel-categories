<?php

declare(strict_types=1);

namespace Rinvex\Categorizable\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:migrate:categorizable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Rinvex Categorizable Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Migrate rinvex/categorizable:');
        $this->call('migrate', ['--step' => true, '--path' => 'vendor/rinvex/categorizable/database/migrations']);
    }
}
