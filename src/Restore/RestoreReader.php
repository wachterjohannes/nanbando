<?php

namespace Nanbando\Restore;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\Storage;
use Nanbando\Tar\TarFactory;
use Symfony\Component\Filesystem\Filesystem;

class RestoreReader
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var TarFactory
     */
    private $factory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(Storage $storage, TarFactory $factory, Filesystem $filesystem, OutputFormatter $output)
    {
        $this->storage = $storage;
        $this->factory = $factory;
        $this->filesystem = $filesystem;
        $this->output = $output;
    }

    public function open(string $name): RestoreArchiveInterface
    {
        $archive = $this->storage->get($name);
        if (!$archive->isFetched()) {
            $this->output->section()->subHeadline('Fetch %s', $name);
            $archive = $this->storage->fetch($name, $this->output);
        }

        $database = $archive->openDatabase();
        $restoreArchive = new RestoreArchive(
            $archive->getArchivePath(),
            $this->factory->create(),
            $database,
            $this->filesystem
        );

        if ($database->has('mode') && BackupArchiveInterface::BACKUP_MODE_DIFFERENTIAL === $database->get('mode')) {
            $parent = $this->open($database->get('parent'));

            return new DifferentialRestoreArchive($restoreArchive, $parent);
        }

        return $restoreArchive;
    }
}
