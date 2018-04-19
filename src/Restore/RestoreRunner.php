<?php

namespace Nanbando\Restore;

use Nanbando\Clock\ClockInterface;
use Nanbando\Console\OutputFormatter;
use Nanbando\Script\ScriptInterface;
use Nanbando\Script\ScriptRegistry;
use Nanbando\TempFileManager\TempFileManagerInterface;

class RestoreRunner
{
    /**
     * @var ClockInterface
     */
    private $clock;

    /**
     * @var ScriptRegistry
     */
    private $scriptRegistry;

    /**
     * @var RestoreReader
     */
    private $restoreReader;

    /**
     * @var TempFileManagerInterface
     */
    private $tempFileManager;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(
        ClockInterface $clock,
        ScriptRegistry $scriptRegistry,
        RestoreReader $restoreReader,
        TempFileManagerInterface $tempFileManager,
        OutputFormatter $output
    ) {
        $this->clock = $clock;
        $this->scriptRegistry = $scriptRegistry;
        $this->restoreReader = $restoreReader;
        $this->tempFileManager = $tempFileManager;
        $this->output = $output;
    }

    public function run(string $name): void
    {
        $started = $this->clock->getDateTime();

        $restoreArchive = $this->restoreReader->open($name);

        $this->output->headline('Restore started at %s', $started);
        $this->output->list(
            [
                'label' => $restoreArchive->get('label'),
                'message' => $restoreArchive->get('message'),
                'started' => $restoreArchive->get('started'),
                'finished' => $restoreArchive->get('finished'),
            ]
        );

        foreach ($this->scriptRegistry->get() as $name => $script) {
            $this->runScript(new RestoreArchiveDecorator($name, $restoreArchive), $name, $script);
        }

        $restoreArchive->close();
        $this->cleanupTempFiles();

        $this->output->info('Restore finished at %s', $started);
    }

    protected function runScript(RestoreArchiveInterface $restoreArchive, string $name, ScriptInterface $script): void
    {
        $sectionOutput = $this->output->section();
        $sectionOutput->headline('Executing script %s', $name);

        $script->restore($restoreArchive, $sectionOutput);

        $sectionOutput->clear();
        $sectionOutput->checkmark('Executing script %s', $name);
    }

    protected function cleanupTempFiles(): void
    {
        $sectionOutput = $this->output->section();
        $sectionOutput->headline('Cleanup temporary files');
        $this->tempFileManager->cleanup($sectionOutput);

        $sectionOutput->clear();
        $sectionOutput->checkmark('Cleanup temporary files');
    }
}
