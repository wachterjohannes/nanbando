<?php

namespace Nanbando\Tests\Recipes\Basic;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Nanbando;
use Nanbando\Plugin\PluginInterface;
use Nanbando\Task\TaskInterface;
use Nanbando\Tests\TestCase;
use function Nanbando\attach;

class BackupTest extends TestCase
{
    public function testAttach()
    {
        /** @var BackupTaskCollection $taskCollection */
        $taskCollection = Nanbando::get()->getService(BackupTaskCollection::class);

        $plugin = $this->prophesize(PluginInterface::class);

        attach('test', $plugin->reveal());

        $tasks = $taskCollection->getTasks();
        $this->assertCount(1, $tasks);

        $this->assertInstanceOf(TaskInterface::class, $tasks['test']);
        $this->assertTrue(is_callable($tasks['test']->getCallable()));
    }
}
