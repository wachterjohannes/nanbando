<?php

namespace Nanbando\Storage;

class RemoteStorage
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    /**
     * @var StorageAdapterInterface
     */
    private $adapter;

    public function __construct(LocalStorage $localStorage, StorageAdapterInterface $adapter)
    {
        $this->localStorage = $localStorage;
        $this->adapter = $adapter;
    }

    public function fetch(ArchiveInfo $archiveInfo): void
    {
        $this->fetchDatabase($archiveInfo);
        $this->fetchArchive($archiveInfo);
    }

    public function fetchDatabase(ArchiveInfo $archiveInfo): void
    {
        $this->adapter->fetch($archiveInfo->getDatabaseName(), $archiveInfo->getDatabasePath());
    }

    public function fetchArchive(ArchiveInfo $archiveInfo): void
    {
        $this->adapter->fetch($archiveInfo->getArchiveName(), $archiveInfo->getArchivePath());
    }

    public function exists(ArchiveInfo $archive)
    {
        return $this->adapter->exists($archive->getArchivePath());
    }

    public function push(ArchiveInfo $archiveInfo): void
    {
        $this->adapter->push($archiveInfo->getArchivePath());
        $this->adapter->push($archiveInfo->getDatabasePath());
    }

    /**
     * @return ArchiveInfo[]
     */
    public function listFiles(): array
    {
        $result = [];
        foreach ($this->adapter->listFiles() as $name) {
            $result[] = $this->localStorage->get($name);
        }

        return $result;
    }
}
