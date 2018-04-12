<?php

namespace Nanbando\Backup;

interface BackupArchiveInterface
{
    public function storeFile(string $name, string $path): void;

    public function getFiles(): array;

    public function set(string $name, $value): void;

    public function get(string $name);

    public function getWithDefault(string $name, $default);

    public function all(): array;
}
