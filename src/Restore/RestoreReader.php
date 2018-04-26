<?php

namespace Nanbando\Restore;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Storage\LocalStorage;
use Nanbando\Tar\TarFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;

class RestoreReader
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    /**
     * @var TarFactory
     */
    private $factory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(LocalStorage $localStorage, TarFactory $factory, Filesystem $filesystem)
    {
        $this->localStorage = $localStorage;
        $this->factory = $factory;
        $this->filesystem = $filesystem;
    }

    public function open(string $name): RestoreArchiveInterface
    {
        $archive = $this->localStorage->get($name);
        if (!$archive->isFetched()) {
            throw new \RuntimeException('Archive "' . $name . '" does not exists in local storage.');
        }

        $database = new ParameterBag(json_decode(file_get_contents($archive->getDatabasePath()), true));

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
