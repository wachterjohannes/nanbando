<?php

namespace Nanbando\File;

class MetadataFactory
{
    /**
     * @var FileHasher
     */
    private $fileHasher;

    public function __construct(FileHasher $fileHasher)
    {
        $this->fileHasher = $fileHasher;
    }

    public function create(string $path): array
    {
        $file = new \SplFileInfo($path);

        return [
            'originalPath' => $path,
            'filename' => $file->getFilename(),
            'type' => $file->getType(),
            'extension' => $file->getExtension(),
            'accessTime' => $file->getATime(),
            'creationTime' => $file->getCTime(),
            'modificationTime' => $file->getMTime(),
            'size' => $file->getSize(),
            'hash' => $this->fileHasher->hash($file->getRealPath()),
        ];
    }
}
