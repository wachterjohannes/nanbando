<?php

namespace Nanbando\Filesystem;

interface FilesystemAdapterInterface
{
    public function getName(): string;

    public function addFile(string $file, string $localName): self;

    public function close(): void;
}
