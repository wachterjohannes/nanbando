<?php

namespace Nanbando\Filesystem;

interface FilesystemInterface
{
    public function decorate(string $prefix): FilesystemInterface;

    public function tempFilename(): string;

    public function addFile(string $file, string $localName): self;

    public function addContent(string $content, string $localName): self;

    public function close(): void;
}
