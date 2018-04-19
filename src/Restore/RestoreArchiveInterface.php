<?php

namespace Nanbando\Restore;

interface RestoreArchiveInterface
{
    public function getMetadata(): array;

    public function fetchFile(string $name, string $path): void;

    public function get(string $name);

    public function getWithDefault(string $name, $default);

    public function close(): void;
}
