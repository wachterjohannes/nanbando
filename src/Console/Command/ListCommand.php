<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(Storage $storage, OutputFormatter $output)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->output = $output;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output->headline('List backups');

        $files = $this->storage->listFiles();
        foreach ($files as $file) {
            $this->output->subHeadline('%s:', $file->getName());

            $this->output->list($this->formatFile($file));
        }
    }

    protected function formatFile(ArchiveInfo $file): array
    {
        if (!$file->isFetched()) {
            $this->storage->fetchDatabase($file);
        }

        $database = $file->openDatabase();

        return [
            'label' => $database->get('label'),
            'message' => $database->get('message'),
            'started' => $database->get('started'),
            'finished' => $database->get('finished'),
        ];
    }
}
