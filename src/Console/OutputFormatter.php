<?php

namespace Nanbando\Console;

use Nanbando\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\ConsoleOutput;

class OutputFormatter extends BaseOutputFormatter
{
    /**
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * @var array
     */
    private $consoleSectionOutputs = [];

    public function __construct(ConsoleOutput $output, string $headlineCharacter = '=')
    {
        parent::__construct($output, $headlineCharacter);
    }

    public function section(): SectionOutputFormatter
    {
        $sectionOutput = new ConsoleSectionOutput(
            $this->output->getStream(),
            $this->consoleSectionOutputs,
            $this->output->getVerbosity(),
            $this->output->isDecorated(),
            $this->output->getFormatter()
        );

        return new SectionOutputFormatter($sectionOutput, $this->output, '-');
    }
}
