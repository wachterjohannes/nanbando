<?php

namespace Nanbando\Tests\Unit\Console;

use Nanbando\Console\TaskCommand;
use Nanbando\Task\TaskInterface;
use Nanbando\Tests\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskCommandTest extends TestCase
{
    public function testRun()
    {
        $task = $this->prophesize(TaskInterface::class);

        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $command = new TaskCommand('test', $task->reveal());

        $this->assertEquals('test', $command->getName());
        $command->run($input->reveal(), $output->reveal());

        $task->invoke()->shouldBeCalled();
    }
}
