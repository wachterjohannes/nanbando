<?php

namespace Nanbando\Console;

use Nanbando\Task\TaskInterface;
use Symfony\Component\Console\Command\Command;
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
        parent::__construct($name);

        $this->task = $task;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->task->invoke();
    }
}
