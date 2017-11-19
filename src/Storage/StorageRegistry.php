<?php

namespace Nanbando\Storage;

class StorageRegistry
{
    /**
     * @var StorageInterface[]
     */
    private $storages = [];

    public function register(string $name, StorageInterface $storage): self
    {
        $this->storages[$name] = $storage;

        return $this;
    }

    public function get(string $name): StorageInterface
    {
        return $this->storages[$name];
    }
}
