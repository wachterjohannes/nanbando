<?php

namespace Nanbando\Storage;

class StorageRegistry
{
    /**
     * @var StorageInterface[]
     */
    private $storages;

    public function __construct(array $storages = [])
    {
        $this->storages = $storages;
    }

    public function get(string $name): StorageInterface
    {
        return $this->storages[$name];
    }
}
