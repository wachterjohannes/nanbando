<?php

namespace Nanbando\Filesystem;

use Webmozart\PathUtil\Path;

class FilesystemDecorator implements FilesystemInterface
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

    public function decorate(string $prefix): FilesystemInterface
    {
        return $this->filesystem->decorate($prefix);
    }

    public function tempFilename(): string
    {
        return $this->filesystem->tempFilename();
    }

    public function addFile(string $file, string $localName): FilesystemInterface
    {
        $this->filesystem->addFile($file, Path::join([$this->prefix, $localName]));

        return $this;
    }

    public function addContent(string $content, string $localName): FilesystemInterface
    {
        $this->filesystem->addContent($content, Path::join([$this->prefix, $localName]));

        return $this;
    }

    public function close(): void
    {
        $this->filesystem->close();
    }
}
