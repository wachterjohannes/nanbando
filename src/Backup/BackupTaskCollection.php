<?php

namespace Nanbando\Backup;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Console\Application;
use Nanbando\Filesystem\FilesystemFactory;
use Nanbando\Task\Task;
use Nanbando\Task\TaskCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BackupTaskCollection extends TaskCollection
{
    /**
     * @var FilesystemFactory
     */
    private $filesystemFactory;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var SymfonyStyle
     */
    private $style;

    /**
     * @var BackupContext
     */
    private $context;

    public function __construct(
        FilesystemFactory $filesystemFactory,
        Application $application,
        OutputInterface $output,
        InputInterface $input
    ) {
        parent::__construct();

        $this->filesystemFactory = $filesystemFactory;
        $this->application = $application;
        $this->output = $output;
        $this->input = $input;
        $this->style = new SymfonyStyle($input, $output);

        $this->before([$this, 'initialize']);
        $this->after([$this, 'finish']);
    }

    public function initialize()
    {
        // TODO version, name
        $version = '0.1';
        $name = 'nanbando';

        $label = $this->input->getArgument('label');
        $message = $this->input->getOption('message');
        $process = $this->application->getProcess();
        $started = new \DateTime();

        $this->context = new BackupContext($this->filesystemFactory->create($label));

        $this->context->set('label', $label);
        $this->context->set('message', $message);
        $this->context->set('started', new \DateTime());
        $this->context->set('nanbando_version', $version);
        $this->context->set('process', $process);

        $this->output->writeln(sprintf('Backup "%s" started:', $name));
        $this->output->writeln(sprintf(' * label:    %s', $label));
        $this->output->writeln(sprintf(' * message:  %s', $message));
        $this->output->writeln(sprintf(' * started:  %s', $started->format(\DateTime::RFC3339)));
        $this->output->writeln(sprintf(' * nanbando: %s', $version));

        if ($process) {
            $this->output->writeln(sprintf(' * process: %s', $process));
        }

        $this->output->writeln('');

        if (count($this->getTasks()) === 0 && $process) {
            $this->style->warning(sprintf('No backup is configured for process "%s".', $process));
            $this->output->writeln('');
        } elseif (count($this->getTasks()) === 0) {
            $this->style->warning('No backup is configured.');
            $this->output->writeln('');
        }

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
        if (count($this->getTasks()) === 0) {
            return;
        }

        $backupName = $this->context->getFilesystem()->getName();

        // TODO status
        $status = 'successfully';

        $this->context->set('finished', new \DateTime());

        $this->context = $this->context->close();

        $this->output->writeln(sprintf('Backup "%s" finished %s', $backupName, $status));
    }
}
