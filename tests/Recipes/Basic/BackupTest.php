<?php

namespace Nanbando\Tests\Recipes\Basic;

use Nanbando\Backup\BackupTaskCollection;
use Nanbando\Console\Application;
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

    public function testAttachProcessNoProcess()
    {
        $nanbando = Nanbando::get();
        $nanbando->import(__DIR__ . '/../../Resources/config/services.yaml');

        $nanbando->getService(Application::class)->mockProcess('test');

        /** @var BackupTaskCollection $taskCollection */
        $taskCollection = $nanbando->getService(BackupTaskCollection::class);

        $plugin = $this->prophesize(PluginInterface::class);

        attach('test', $plugin->reveal());

        $tasks = $taskCollection->getTasks();
        $this->assertCount(1, $tasks);

        $this->assertInstanceOf(TaskInterface::class, $tasks['test']);
        $this->assertTrue(is_callable($tasks['test']->getCallable()));
    }

    public function testAttachProcessWrongProcess()
    {
        $nanbando = Nanbando::get();
        $nanbando->import(__DIR__ . '/../../Resources/config/services.yaml');

        $nanbando->getService(Application::class)->mockProcess('test');

        /** @var BackupTaskCollection $taskCollection */
        $taskCollection = $nanbando->getService(BackupTaskCollection::class);

        $plugin = $this->prophesize(PluginInterface::class);

        attach('test', $plugin->reveal(), ['wrong-process']);

        $tasks = $taskCollection->getTasks();
        $this->assertCount(0, $tasks);
    }

    public function testAttachProcessCorrectProcess()
    {
        $nanbando = Nanbando::get();
        $nanbando->import(__DIR__ . '/../../Resources/config/services.yaml');

        $nanbando->getService(Application::class)->mockProcess('test');

        /** @var BackupTaskCollection $taskCollection */
        $taskCollection = $nanbando->getService(BackupTaskCollection::class);

        $plugin = $this->prophesize(PluginInterface::class);

        attach('test', $plugin->reveal(), ['test']);

        $tasks = $taskCollection->getTasks();
        $this->assertCount(1, $tasks);

        $this->assertInstanceOf(TaskInterface::class, $tasks['test']);
        $this->assertTrue(is_callable($tasks['test']->getCallable()));
    }
}
