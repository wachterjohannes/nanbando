<?php

namespace Nanbando\Filesystem;

interface FilesystemInterface
{
    public function addFile(string $file, string $localName): self;

    public function close(): void;
}
