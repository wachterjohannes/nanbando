<?php

namespace Nanbando\Filesystem;

use Webmozart\PathUtil\Path;

class PrefixedFilesystem implements FilesystemInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(FilesystemInterface $filesystem, $prefix)
    {
        $this->filesystem = $filesystem;
        $this->prefix = $prefix;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function addFile(string $file, string $localName): FilesystemInterface
    {
        return $this->filesystem->addFile($file, Path::join([$this->prefix, $localName]));
    }

    public function close(): void
    {
        $this->filesystem->close();
    }
}
