<?php

namespace Nanbando\Tests\Unit\Console;

use Nanbando\Console\TaskCommand;
use Nanbando\Console\TaskCommandFactory;
use Nanbando\Task\TaskInterface;
use Nanbando\Tests\TestCase;

class TaskCommandFactoryTest extends TestCase
{
    public function testCreate()
    {
        $task = $this->prophesize(TaskInterface::class);

        $taskCommandFactory = new TaskCommandFactory();

        $taskCommand = $taskCommandFactory->create('test', $task->reveal());
        $this->assertInstanceOf(TaskCommand::class, $taskCommand);
        $this->assertEquals('test', $taskCommand->getName());
    }
}
