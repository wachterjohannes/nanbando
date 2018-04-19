<?php

namespace Nanbando\Restore;

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

        $database = json_decode(file_get_contents($archive->getDatabasePath()), true);

        return new RestoreArchive(
            $archive->getArchivePath(),
            $this->factory->create(),
            new ParameterBag($database),
            $this->filesystem
        );
    }
}
