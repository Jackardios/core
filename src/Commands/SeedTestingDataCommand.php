<?php

namespace Laraneat\Core\Commands;

use Illuminate\Console\Command;

class SeedTestingDataCommand extends Command
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
