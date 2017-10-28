<?php

namespace Nanbando\Console;

use Nanbando\Task\TaskInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskCommand extends Command
{
    /**
     * @var TaskInterface
     */
    private $task;

    public function __construct(string $name, TaskInterface $task)
    {
        $this->task = $task;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription($this->task->getDescription());

        foreach ($this->task->getOptions() as $option) {
            $this->getDefinition()->addOption($option);
        }

        foreach ($this->task->getArguments() as $argument) {
            $this->getDefinition()->addArgument($argument);
        }
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $this->task->invoke();
    }
}
