<?php

namespace Nanbando\Storage;

class StorageRegistry
{
    /**
     * @var RemoteStorage[]
     */
    private $storages;

    public function __construct(array $storages = [])
    {
        $this->storages = $storages;
    }

    public function get(string $name): RemoteStorage
    {
        return $this->storages[$name];
    }
}
