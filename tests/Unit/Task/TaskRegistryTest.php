<?php

namespace Nanbando\Tests\Unit\Task;

use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;
use PHPUnit\Framework\TestCase;

class TaskRegistryTest extends TestCase
{
    public function testRegister()
    {
        $task = $this->prophesize(TaskInterface::class);

        $registry = new TaskRegistry();

        $this->assertEquals($registry, $registry->register('test', $task->reveal()));
        $this->assertEquals(['test' => $task->reveal()], $registry->getTasks());
    }
}
