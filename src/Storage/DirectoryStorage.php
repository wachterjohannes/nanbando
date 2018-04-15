<?php

namespace Nanbando\Storage;

use Symfony\Component\Filesystem\Filesystem;

class DirectoryStorage implements StorageInterface
{
    public static function create(string $directory): StorageInterface
    {
        return new self($directory, new Filesystem());
    }

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $directory, Filesystem $filesystem)
    {
        $this->directory = $directory;
        $this->filesystem = $filesystem;
    }

    public function push(string $filePath): void
    {
        $this->filesystem->copy($filePath, $this->directory . '/' . basename($filePath));
    }

    public function exists(string $filePath): bool
    {
        return $this->filesystem->exists($this->directory . '/' . basename($filePath));
    }
}
