<?php

namespace Nanbando\Backup;

interface BackupArchiveInterface
{
    const BACKUP_MODE_FULL = 'full';
    const BACKUP_MODE_DIFFERENTIAL = 'differential';

    public function storeFile(string $name, string $path, ?array $metadata = null): void;

    public function storeMetadata(string $name, array $metadata): void;

    public function getFiles(): array;

    public function set(string $name, $value): void;

    public function get(string $name);

    public function getWithDefault(string $name, $default);

    public function all(): array;
}
