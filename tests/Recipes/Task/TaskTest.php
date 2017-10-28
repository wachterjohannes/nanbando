<?php

namespace Nanbando\Tests\Recipes\Task;

use Nanbando\Nanbando;
use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;
use Nanbando\Tests\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Nanbando\registerTask;
use function Nanbando\task;

class TaskTest extends TestCase
{
    public function testTask()
    {
        /** @var TaskRegistry $taskRegistry */
        $taskRegistry = Nanbando::get()->getService(TaskRegistry::class);

        $callable = [$this, 'testRegisterTask'];
        $task = task('test', $callable);

        $this->assertInstanceOf(TaskInterface::class, $task);
        $this->assertEquals(['test' => $task], $taskRegistry->getTasks());
    }

    public function testRegisterTask()
    {
        $nanbando = Nanbando::get();

        /** @var TaskRegistry $taskRegistry */
        $taskRegistry = $nanbando->getService(TaskRegistry::class);

        $task = $this->prophesize(TaskInterface::class);

        $task->setParameter(
            [$nanbando->getService(InputInterface::class), $nanbando->getService(OutputInterface::class)]
        )->shouldBeCalled()->willReturn($task->reveal());

        registerTask('test', $task->reveal());

        $this->assertEquals(['test' => $task->reveal()], $taskRegistry->getTasks());
    }
}
