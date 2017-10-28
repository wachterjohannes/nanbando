<?php

namespace Nanbando\Filesystem;

class Filesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    private $tempDirectory;

    /**
     * @var FilesystemAdapterInterface
     */
    private $adapter;

    public function __construct(FilesystemAdapterInterface $adapter, string $tempDirectory)
    {
        $this->tempDirectory = $tempDirectory;
        $this->adapter = $adapter;
    }

    public function getName(): string
    {
        return $this->adapter->getName();
    }

    public function decorate(string $prefix): FilesystemInterface
    {
        return new FilesystemDecorator($this, $prefix);
    }

    public function tempFilename(): string
    {
        return tempnam($this->tempDirectory, 'nanbando');
    }

    public function addFile(string $file, string $localName): FilesystemInterface
    {
        $this->adapter->addFile($file, $localName);

        return $this;
    }

    public function addContent(string $content, string $localName): FilesystemInterface
    {
        $tempFilename = $this->tempFilename();
        file_put_contents($tempFilename, $content);

        $this->addFile($tempFilename, $localName);

        unlink($tempFilename);

        return $this;
    }

    public function close(): void
    {
        $this->adapter->close();
    }
}
