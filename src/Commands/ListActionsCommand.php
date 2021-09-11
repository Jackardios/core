<?php

namespace Laraneat\Core\Commands;

use Illuminate\Console\Command;
use Laraneat\Core\Foundation\Facades\Laraneat;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\ConsoleOutput;

class ListActionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = "laraneat:list:actions {--withfilename}";

    /**
     * The console command description.
     */
    protected $description = "List all Actions in the Application.";

    protected ConsoleOutput $console;

    public function __construct(ConsoleOutput $console)
    {
        parent::__construct();

        $this->console = $console;
    }

    public function handle(): void
    {
        foreach (Laraneat::getSectionNames() as $sectionName) {
            foreach (Laraneat::getSectionContainerNames($sectionName) as $containerName) {
                $this->console->writeln("<fg=yellow> [$containerName]</fg=yellow>");

                $directory = base_path('app/Containers/' . $sectionName . '/' . $containerName . '/Actions');

                if (File::isDirectory($directory)) {
                    $files = File::allFiles($directory);

                    foreach ($files as $action) {
                        // Get the file name as is
                        $fileName = $originalFileName = $action->getFilename();

                        // Remove the Action.php postfix from each file name
                        // Further, remove the `.php', if the file does not end on 'Action.php'
                        $fileName = str_replace(array('Action.php', '.php'), '', $fileName);

                        // UnCamelize the word and replace it with spaces
                        $fileName = uncamelize($fileName);

                        // Check if flag exists
                        $includeFileName = '';
                        if ($this->option('withfilename')) {
                            $includeFileName = "<fg=red>($originalFileName)</fg=red>";
                        }

                        $this->console->writeln("<fg=green>  - $fileName</fg=green>  $includeFileName");
                    }
                }
            }
        }
    }
}
