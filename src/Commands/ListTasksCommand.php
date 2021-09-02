<?php

namespace Laraneat\Core\Commands;

use Laraneat\Core\Abstracts\Commands\ConsoleCommand;
use Laraneat\Core\Foundation\Facades\Laraneat;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\ConsoleOutput;

class ListTasksCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = "laraneat:list:tasks {--withfilename}";

    /**
     * The console command description.
     */
    protected $description = "List all Tasks in the Application.";

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

                $directory = base_path('app/Containers/' . $sectionName . '/' . $containerName . '/Tasks');

                if (File::isDirectory($directory)) {
                    $files = File::allFiles($directory);

                    foreach ($files as $action) {
                        // Get the file name as is
                        $fileName = $originalFileName = $action->getFilename();

                        // Remove the Task.php postfix from each file name
                        // Further, remove the `.php', if the file does not end on 'Task.php'
                        $fileName = str_replace(array('Task.php', '.php'), '', $fileName);

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
