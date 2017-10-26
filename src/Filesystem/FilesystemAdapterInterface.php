<?php

namespace Nanbando\Filesystem;

interface FilesystemAdapterInterface
{
    public function addFile(string $file, string $localName): self;

    public function close(): void;
}
