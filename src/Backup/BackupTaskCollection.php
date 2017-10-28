<?php

namespace Nanbando\Backup;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemFactory;
use Nanbando\Task\Task;
use Nanbando\Task\TaskCollection;
use Symfony\Component\Console\Output\OutputInterface;

class BackupTaskCollection extends TaskCollection
{
    /**
     * @var FilesystemFactory
     */
    private $filesystemFactory;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var BackupContext
     */
    private $context;

    public function __construct(FilesystemFactory $filesystemFactory, OutputInterface $output)
    {
        parent::__construct();

        $this->filesystemFactory = $filesystemFactory;
        $this->output = $output;

        $this->before([$this, 'initialize']);
        $this->after([$this, 'finish']);
    }

    public function initialize()
    {
        // TODO label, message, version, name
        $label = '';
        $message = '';
        $version = '0.1';
        $name = 'nanbando';

        $this->context = new BackupContext($this->filesystemFactory->create());

        $this->context->set('label', $label);
        $this->context->set('message', $message);
        $this->context->set('started', (new \DateTime())->format(\DateTime::RFC3339));
        $this->context->set('nanbando_version', $version);

        $this->output->writeln(sprintf('Backup "%s" started:', $name));
        $this->output->writeln(sprintf(' * label:    %s', $this->context->get('label')));
        $this->output->writeln(sprintf(' * message:  %s', $this->context->get('message')));
        $this->output->writeln(sprintf(' * started:  %s', $this->context->get('started')));
        $this->output->writeln(sprintf(' * nanbando: %s', $this->context->get('nanbando_version')));
        $this->output->writeln('');

        $this->beforeAll(
            function (string $name, Task $task) {
                $this->context = $this->context->open($name);
                $this->context->set('started', new \DateTime());
                $task->setParameter(array_merge([$this->context], $task->getParameter()));

                $this->output->writeln('- ' . $name . ':');
            }
        );

        $this->afterAll(
            function () {
                $this->output->writeln('');

                $this->context->set('finished', new \DateTime());
                $this->context = $this->context->close();
            }
        );
    }

    public function finish()
    {
        $backupName = $this->context->getFilesystem()->getName();

        // TODO name, status
        $status = 'successfully';

        $this->context->set('finished', new \DateTime());

        $this->context = $this->context->close();

        $this->output->writeln(sprintf('Backup "%s" finished %s', $backupName, $status));
    }
}
