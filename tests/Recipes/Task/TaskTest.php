<?php

namespace Nanbando\Tests\Recipes\Task;

use Nanbando\Nanbando;
use function Nanbando\registerTask;
use Nanbando\Task\TaskInterface;
use Nanbando\Task\TaskRegistry;
use Nanbando\Tests\TestCase;

class TaskTest extends TestCase
{
    public function testRegisterTask()
    {
        /** @var TaskRegistry $taskRegistry */
        $taskRegistry = Nanbando::get()->getService(TaskRegistry::class);

        $callable = [$this, 'testRegisterTask'];
        $task = registerTask('test', $callable);

        $this->assertInstanceOf(TaskInterface::class, $task);

        $this->assertEquals(['test' => $task], $taskRegistry->getTasks());
    }
}
