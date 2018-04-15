<?php

namespace Nanbando\Storage;

use Nanbando\Console\OutputFormatter;

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

    public function push(OutputFormatter $output): void
    {
        foreach ($this->localStorage->listFiles() as $archiveInfo) {
            if (!$this->adapter->exists($archiveInfo->getName())) {
                $this->adapter->push($archiveInfo->getArchivePath());
                $this->adapter->push($archiveInfo->getDatabasePath());
            }

            $output->section()->checkmark($archiveInfo->getName());
        }
    }

    public function fetch(OutputFormatter $output): void
    {
        foreach ($this->adapter->listFiles() as $name) {
            $archiveInfo = $this->localStorage->get($name);
            if (!$archiveInfo->isFetched()) {
                $this->adapter->fetch($archiveInfo->getArchiveName(), $archiveInfo->getArchivePath());
                $this->adapter->fetch($archiveInfo->getDatabaseName(), $archiveInfo->getDatabasePath());
            }

            $output->section()->checkmark($archiveInfo->getName());
        }
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
