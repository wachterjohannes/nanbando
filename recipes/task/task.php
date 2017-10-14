<?php

namespace Nanbando;

use Nanbando\Task\Task;
use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

function registerTask(string $name, callable $callable): TaskInterface
{
    $nanbando = Nanbando::get();

    /** @var TaskRegistry $taskRegistry */
    $taskRegistry = $nanbando->getService(TaskRegistry::class);

    $input = $nanbando->getService(InputInterface::class);
    $output = $nanbando->getService(OutputInterface::class);

    $task = new Task($callable, [$input, $output]);
    $taskRegistry->register($name, $task);

    return $task;
}
