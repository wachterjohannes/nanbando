<?php

namespace Nanbando;

use Nanbando\Task\Task;
use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

function task(string $name, callable $callable): TaskInterface
{
    $task = new Task($callable);

    return registerTask($name, $task);
}

function registerTask(string $name, TaskInterface $task)
{
    $nanbando = Nanbando::get();

    /** @var TaskRegistry $taskRegistry */
    $taskRegistry = $nanbando->getService(TaskRegistry::class);

    $input = $nanbando->getService(InputInterface::class);
    $output = $nanbando->getService(OutputInterface::class);

    $task->setParameter([$input, $output]);

    $taskRegistry->register($name, $task);

    return $task;
}
