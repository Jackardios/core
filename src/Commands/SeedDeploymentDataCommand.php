<?php

namespace Laraneat\Core\Commands;

use Laraneat\Core\Abstracts\Commands\ConsoleCommand;

class SeedDeploymentDataCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = "laraneat:seed-deploy";

    /**
     * The console command description.
     */
    protected $description = "Seed data for initial deployment.";

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => config('laraneat.seeders.deployment')
        ]);

        $this->info('Deployment Data Seeded Successfully.');
    }
}
