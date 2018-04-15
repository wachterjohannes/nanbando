<?php

namespace Nanbando\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Webmozart\PathUtil\Path;

class DirectoryStorageAdapter implements StorageAdapterInterface
{
    public static function create(string $directory): StorageAdapterInterface
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

    public function listFiles(): array
    {
        if (!is_dir($this->directory)) {
            return [];
        }

        $result = [];
        /** @var \SplFileInfo $file */
        foreach ((new Finder())->files()->in($this->directory) as $file) {
            $result[] = explode('.', $file->getFilename())[0];
        }

        return array_unique($result);
    }

    public function fetch(string $name, string $destination): void
    {
        $this->filesystem->copy(Path::join($this->directory, $name), $destination, true);
    }
}
