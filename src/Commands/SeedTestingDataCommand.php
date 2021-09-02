<?php

namespace Laraneat\Core\Commands;

use Laraneat\Core\Abstracts\Commands\ConsoleCommand;

class SeedTestingDataCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = "laraneat:seed-test";

    /**
     * The console command description.
     */
    protected $description = "Seed testing data.";

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => config('laraneat.seeders.testing')
        ]);

        $this->info('Testing Data Seeded Successfully.');
    }
}
