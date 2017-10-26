<?php

namespace Nanbando\Task;

class TaskCollection extends Task implements TaskInterface
{
    /**
     * @var Task[]
     */
    private $tasks = [];

    public function __construct()
    {
        parent::__construct([$this, 'invokeTasks']);
    }

    public function register(string $name, TaskInterface $task): self
    {
        $this->tasks[$name] = $task;

        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function beforeAll(callable $callable, array $parameter = []): self
    {
        foreach ($this->tasks as $name => $task) {
            $task->before($callable, array_merge($parameter, [$name, $task]));
        }

        return $this;
    }

    public function afterAll(callable $callable, array $parameter = []): self
    {
        foreach ($this->tasks as $name => $task) {
            $task->after($callable, array_merge($parameter, [$name, $task]));
        }

        return $this;
    }

    protected function invokeTasks()
    {
        $results = [];
        foreach ($this->tasks as $name => $task) {
            $results[$name] = $task->invoke();
        }

        return $results;
    }
}
