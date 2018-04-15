<?php

namespace Nanbando\Console;

use Nanbando\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class SectionOutputFormatter extends BaseOutputFormatter
{
    const PROGRESS_BAR_WITH_MAX = '%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%';
    const PROGRESS_BAR_WITHOUT_MAX = '%current% [%bar%] %elapsed:6s% %memory:6s%';

    /**
     * @var ConsoleSectionOutput
     */
    protected $output;

    /**
     * @var ConsoleOutput
     */
    protected $consoleOutput;

    public function __construct(
        ConsoleSectionOutput $output,
        ConsoleOutput $consoleOutput,
        string $headlineCharacter = '='
    ) {
        parent::__construct($output, $headlineCharacter);

        $this->consoleOutput = $consoleOutput;
    }

    public function clear(?int $lines = null): void
    {
        $this->output->clear($lines);
        $this->consoleOutput->write("\r");
    }

    public function progressBar(int $count = 0)
    {
        $progressBar = new ProgressBar($this->consoleOutput, $count);
        $progressBar->setOverwrite(true);
        $progressBar->setFormat($count ? self::PROGRESS_BAR_WITH_MAX : self::PROGRESS_BAR_WITHOUT_MAX);
        $progressBar->start();

        return $progressBar;
    }

    public function checkmark($format, ...$arguments)
    {
        $line = sprintf($format, ...$this->formatValues($arguments));
        $this->output->writeln('<info>' . "\xE2\x9C\x94" . '</info> ' . $line);
    }
}
