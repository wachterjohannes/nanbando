<?php

namespace Nanbando\Storage;

use Symfony\Component\Filesystem\Filesystem;

class DirectoryStorage implements StorageInterface
{
    public static function create(string $directory)
    {
        return new self($directory);
    }

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $directory, Filesystem $filesystem = null)
    {
        $this->directory = $directory;

        $this->filesystem = $filesystem ?: new Filesystem();
    }

    public function upload(string $filename, string $localPath): StorageInterface
    {
        $this->filesystem->copy($localPath, sprintf('%s/%s.tar.gz', $this->directory, $filename), true);

        return $this;
    }

    public function download(string $filename, string $localPath): StorageInterface
    {
        $this->filesystem->copy(sprintf('%s/%s.tar.gz', $this->directory, $filename), $localPath, true);

        return $this;
    }

    public function listFiles(): array
    {
        $result = [];
        foreach (glob(sprintf('%s/*.tar.gz', $this->directory)) as $file) {
            $result[] = basename($file, '.tar.gz');
        }

        return $result;
    }

    public function exists(string $filename)
    {
        return $this->filesystem->exists(sprintf('%s/%s.tar.gz', $this->directory, $filename));
    }
}
