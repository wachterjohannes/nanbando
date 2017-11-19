<?php

namespace Nanbando\Storage;

interface StorageInterface
{
    public function upload(string $filename, string $localPath): self;

    public function download(string $filename, string $localPath): self;

    public function listFiles(): array;

    public function exists(string $filename);
}
