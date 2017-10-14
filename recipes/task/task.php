<?php

namespace Nanbando;

use Nanbando\Task\Task;
use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;

function registerTask(string $name, callable $callable): TaskInterface
{
    /** @var TaskRegistry $taskRegistry */
    $taskRegistry = Nanbando::get()->getService(TaskRegistry::class);

    $task = new Task($callable);
    $taskRegistry->register($name, new Task($callable));

    return $task;
}
