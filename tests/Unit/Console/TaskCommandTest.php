<?php

namespace Nanbando\Tests\Unit\Console;

use Nanbando\Console\Application;
use Nanbando\Console\TaskCommand;
use Nanbando\Host\Localhost;
use Nanbando\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TaskCommandTest extends TestCase
{
    public function testRun()
    {
        $options = [$this->prophesize(InputOption::class)->reveal()];
        $arguments = [$this->prophesize(InputArgument::class)->reveal()];

        $task = $this->prophesize(TaskInterface::class);
        $task->getOptions()->willReturn($options);
        $task->getArguments()->willReturn($arguments);
        $task->getDescription()->willReturn('Test description');

        $task->invoke()->shouldBeCalled();

        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $application = $this->prophesize(Application::class);
        $application->getHelperSet()->willReturn(new HelperSet());
        $application->getDefinition()->willReturn(new InputDefinition());
        $application->getHost()->willReturn(new Localhost());

        $command = new TaskCommand('test', $task->reveal());
        $command->setApplication($application->reveal());

        $this->assertEquals('test', $command->getName());
        $this->assertEquals('Test description', $command->getDescription());
        $this->assertEquals($options, array_values($command->getDefinition()->getOptions()));
        $this->assertEquals($arguments, array_values($command->getDefinition()->getArguments()));

        $this->assertEquals('test', $command->getName());
        $command->run($input->reveal(), $output->reveal());
    }
}
