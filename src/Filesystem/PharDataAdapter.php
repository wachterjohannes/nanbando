<?php

namespace Nanbando\Filesystem;

class PharDataAdapter implements FilesystemAdapterInterface
{
    /**
     * @var \PharData
     */
    private $pharData;

    /**
     * @var string
     */
    private $filename;

    public function __construct(\PharData $pharData, string $filename)
    {
        $this->pharData = $pharData;
        $this->filename = $filename;
    }

    public function getName(): string
    {
        return basename($this->filename);
    }

    public function addFile(string $file, string $localName): FilesystemAdapterInterface
    {
        $this->pharData->addFile($file, $localName);

        return $this;
    }

    public function close(): void
    {
        $this->pharData->compress(\Phar::GZ);
        unlink($this->filename);
    }
}
