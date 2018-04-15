<?php

namespace Nanbando\Storage;

interface StorageInterface
{
    public function push(string $filePath): void;

    public function fetch(string $name, string $destination): void;

    public function exists(string $filePath): bool;

    public function listFiles(): array;
}
