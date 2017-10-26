<?php

namespace Nanbando\Tests\Unit\Task;

use Nanbando\Task\TaskCollection;
use Nanbando\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class TaskCollectionTest extends TestCase
{
    public function testRegister()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $this->assertEquals($taskCollection, $taskCollection->register('test', $task->reveal()));

        $task->invoke()->shouldBeCalled()->willReturn($task->reveal());

        $taskCollection->invoke();
    }

    public function testBeforeAll()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $this->assertEquals($taskCollection, $taskCollection->register('test', $task->reveal()));

        $callable = [$this, 'testBeforeAll'];
        $task->before($callable, ['test', $task->reveal()])->shouldBeCalled()->willReturn($task->reveal());

        $this->assertEquals($taskCollection, $taskCollection->beforeAll($callable));
    }

    public function testBeforeAllWithParameter()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $this->assertEquals($taskCollection, $taskCollection->register('test', $task->reveal()));

        $callable = [$this, 'testBeforeAll'];
        $task->before($callable, [1, 'test', $task->reveal()])->shouldBeCalled()->willReturn($task->reveal());

        $this->assertEquals($taskCollection, $taskCollection->beforeAll($callable, [1]));
    }

    public function testAfterAll()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $taskCollection->register('test', $task->reveal());

        $callable = [$this, 'testAfterAll'];
        $task->after($callable, ['test', $task->reveal()])->shouldBeCalled()->willReturn($task->reveal());

        $this->assertEquals($taskCollection, $taskCollection->afterAll($callable));
    }

    public function testAfterAllWithParameter()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $taskCollection->register('test', $task->reveal());

        $callable = [$this, 'testAfterAll'];
        $task->after($callable, [1, 'test', $task->reveal()])->shouldBeCalled()->willReturn($task->reveal());

        $this->assertEquals($taskCollection, $taskCollection->afterAll($callable, [1]));
    }

    public function testGetTasks()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCollection = new TaskCollection();
        $taskCollection->register('test', $task->reveal());

        $this->assertEquals(['test' => $task->reveal()], $taskCollection->getTasks());
    }
}
