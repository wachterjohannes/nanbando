<?php

namespace Nanbando\Console;

use Nanbando\Task\TaskInterface;

class TaskCommandFactory
{
    public function create(string $name, TaskInterface $task)
    {
        return new TaskCommand($name, $task);
    }
}
