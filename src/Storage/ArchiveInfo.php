<?php

namespace Nanbando\Storage;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ArchiveInfo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $archivePath;

    /**
     * @var string
     */
    private $databasePath;

    /**
     * @var bool
     */
    private $fetched;

    public function __construct(string $name, string $archivePath, string $databasePath, bool $fetched)
    {
        $this->name = $name;
        $this->archivePath = $archivePath;
        $this->databasePath = $databasePath;
        $this->fetched = $fetched;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArchivePath(): string
    {
        return $this->archivePath;
    }

    public function getArchiveName(): string
    {
        return basename($this->archivePath);
    }

    public function getDatabasePath(): string
    {
        return $this->databasePath;
    }

    public function getDatabaseName(): string
    {
        return basename($this->databasePath);
    }

    public function isFetched(): bool
    {
        return $this->fetched;
    }

    public function openDatabase(): ParameterBagInterface
    {
        $parameter = json_decode(file_get_contents($this->getDatabasePath()), true);

        return new ParameterBag($parameter);
    }
}
