<?php

namespace Laraneat\Core\Commands;

use Illuminate\Console\Command;
use Laraneat\Core\Foundation\Laraneat;

class GetLaraneatVersionCommand extends Command
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
