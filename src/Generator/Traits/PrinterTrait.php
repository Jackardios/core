<?php

namespace Laraneat\Core\Generator\Traits;

trait PrinterTrait
{
    public function printStartedMessage(string $containerName, string $fileName): void
    {
        $this->printInfoMessage('> Generating (' . $fileName . ') in (' . $containerName . ') Container.');
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function printFinishedMessage(string $type): void
    {
        $this->printInfoMessage($type . ' generated successfully.');
    }

    /**
     * @param string $message
     */
    public function printErrorMessage(string $message): void
    {
        $this->error($message);
    }

    /**
     * @param string $message
     */
    public function printInfoMessage(string $message): void
    {
        $this->info($message);
    }
}
