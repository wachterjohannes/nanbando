<?php

namespace Nanbando\Backup;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemFactory;
use Nanbando\Task\Task;
use Nanbando\Task\TaskCollection;

class BackupTaskCollection extends TaskCollection
{
    /**
     * @var FilesystemFactory
     */
    private $filesystemFactory;

    /**
     * @var BackupContext
     */
    private $context;

    public function __construct(FilesystemFactory $filesystemFactory)
    {
        parent::__construct();

        $this->filesystemFactory = $filesystemFactory;

        $this->before([$this, 'initialize']);
        $this->after([$this, 'finish']);
    }

    public function initialize()
    {
        // TODO label
        $this->context = new BackupContext($this->filesystemFactory->create());

        $this->beforeAll(
            function (string $name, Task $task) {
                $this->context = $this->context->open($name);
                $this->context->set('start', new \DateTime());
                $task->setParameter(array_merge([$this->context], $task->getParameter()));
            }
        );

        $this->afterAll(
            function () {
                $this->context->set('end', new \DateTime());
                $this->context = $this->context->close();
            }
        );

        $this->context->set('start', new \DateTime());
    }

    public function finish()
    {
        $this->context->set('end', new \DateTime());

        $this->context = $this->context->close();
    }
}
