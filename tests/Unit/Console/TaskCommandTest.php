<?php

namespace Nanbando\Tests\Unit\Console;

use Nanbando\Console\Application;
use Nanbando\Console\TaskCommand;
use Nanbando\Host\Localhost;
use Nanbando\Task\TaskInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPUnit\Framework\TestCase;

class TaskCommandTest extends TestCase
{
    public function testRun()
    {
        $task = $this->prophesize(TaskInterface::class);

        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $application = $this->prophesize(Application::class);
        $application->getHelperSet()->willReturn(new HelperSet());
        $application->getDefinition()->willReturn(new InputDefinition());
        $application->getHost()->willReturn(new Localhost());

        $command = new TaskCommand('test', $task->reveal());
        $command->setApplication($application->reveal());

        $this->assertEquals('test', $command->getName());
        $command->run($input->reveal(), $output->reveal());

        $task->invoke()->shouldBeCalled();
    }
}
