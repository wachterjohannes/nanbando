<?php

namespace Nanbando\Tests\Unit\Console;

use Nanbando\Console\TaskCommand;
use Nanbando\Console\TaskCommandFactory;
use Nanbando\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TaskCommandFactoryTest extends TestCase
{
    public function testCreate()
    {
        $options = [$this->prophesize(InputOption::class)->reveal()];
        $arguments = [$this->prophesize(InputArgument::class)->reveal()];

        $task = $this->prophesize(TaskInterface::class);
        $task->getOptions()->willReturn($options);
        $task->getArguments()->willReturn($arguments);
        $task->getDescription()->willReturn('Test description');

        $taskCommandFactory = new TaskCommandFactory();

        $taskCommand = $taskCommandFactory->create('test', $task->reveal());
        $this->assertInstanceOf(TaskCommand::class, $taskCommand);
        $this->assertEquals('test', $taskCommand->getName());
        $this->assertEquals('Test description', $taskCommand->getDescription());
        $this->assertEquals($options, array_values($taskCommand->getDefinition()->getOptions()));
        $this->assertEquals($arguments, array_values($taskCommand->getDefinition()->getArguments()));
    }
}
