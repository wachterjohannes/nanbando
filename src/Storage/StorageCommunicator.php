<?php

namespace Nanbando\Storage;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class StorageCommunicator
{
    /**
     * @var StorageRegistry
     */
    private $storageRegistry;

    /**
     * @var callable
     */
    private $factoryMethod;

    public function __construct(StorageRegistry $storageRegistry, callable $factoryMethod = null)
    {
        $this->storageRegistry = $storageRegistry;
        $this->factoryMethod = $factoryMethod
            ?: function (string $directory) {
                return DirectoryStorage::create($directory);
            };
    }

    public function push(string $directory, InputInterface $input, OutputInterface $output)
    {
        $local = call_user_func_array($this->factoryMethod, [$directory]);
        $remote = $this->storageRegistry->get($input->getArgument('remote'));

        $output->writeln('Upload files:');

        foreach ($local->listFiles() as $file) {
            $output->write(' * ' . $file . ': ');
            if ($remote->exists($file)) {
                $output->writeln('skip (already exists)');

                continue;
            }

            $remote->upload($file, sprintf('%s/%s.tar.gz', $directory, $file));
            $output->writeln('done');
        }
    }

    public function fetch(string $directory, InputInterface $input, OutputInterface $output)
    {
        $local = call_user_func_array($this->factoryMethod, [$directory]);
        $remote = $this->storageRegistry->get($input->getArgument('remote'));

        $file = $input->getOption('file');
        if (!$file && 1 === count($files = $remote->listFiles())) {
            $file = reset($files);
        } elseif (!$file) {
            $question = new ChoiceQuestion('Which file you want to fetch?', $remote->listFiles());
            $questionHelper = new QuestionHelper();

            $file = $questionHelper->ask($input, $output, $question);
        }

        $output->write('Download ' . $file . ': ');
        if ($local->exists($file)) {
            $output->writeln('skip (already exists)');

            return;
        }

        $remote->download($file, sprintf('%s/%s.tar.gz', $directory, $file));
        $output->writeln('done');
    }

    public function list(InputInterface $input, OutputInterface $output)
    {
        $remoteName = $input->getArgument('remote');
        $remote = $this->storageRegistry->get($remoteName);

        $output->writeln('List of available files on "' . $remoteName . '": ');
        foreach ($remote->listFiles() as $file) {
            $output->writeln(' * ' . $file);
        }
    }

    public function listLocal(string $directory, InputInterface $input, OutputInterface $output)
    {
        $local = DirectoryStorage::create($directory);

        $output->writeln('List of available local files: ');
        foreach ($local->listFiles() as $file) {
            $output->writeln(' * ' . $file);
        }
    }
}
