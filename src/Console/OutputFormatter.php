<?php

namespace Nanbando\Console;

use Symfony\Component\Console\Output\ConsoleOutputInterface;

class OutputFormatter extends BaseOutputFormatter
{
    /**
     * @var ConsoleOutputInterface
     */
    protected $output;

    public function __construct(ConsoleOutputInterface $output, string $headlineCharacter = '=')
    {
        parent::__construct($output, $headlineCharacter);
    }

    public function section(): SectionOutputFormatter
    {
        return new SectionOutputFormatter($this->output->section(), '-');
    }
}
