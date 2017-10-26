<?php

namespace Nanbando\Filesystem;

class PharDataFilesystem implements FilesystemInterface
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

    public function addFile(string $file, string $localName): FilesystemInterface
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
