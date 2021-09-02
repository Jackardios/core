<?php

namespace Laraneat\Core\Commands;

use Laraneat\Core\Abstracts\Commands\ConsoleCommand;
use Laraneat\Core\Foundation\Laraneat;

class GetLaraneatVersionCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = "laraneat";

    /**
     * The console command description.
     */
    protected $description = "Display the current Laraneat version.";

    public function handle(): void
    {
        $this->info(Laraneat::VERSION);
    }
}
