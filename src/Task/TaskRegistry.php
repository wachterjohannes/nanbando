<?php

namespace Nanbando\Task;

class TaskRegistry
{
    /**
     * @var TaskInterface[]
     */
    private $tasks = [];

    public function register(string $name, TaskInterface $task): self
    {
        $this->tasks[$name] = $task;

        return $this;
    }

    /**
     * @return TaskInterface[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}
