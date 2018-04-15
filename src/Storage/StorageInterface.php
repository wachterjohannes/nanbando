<?php

namespace Nanbando\Storage;

interface StorageInterface
{
    public function push(string $filePath): void;

    public function exists(string $filePath): bool;
}
