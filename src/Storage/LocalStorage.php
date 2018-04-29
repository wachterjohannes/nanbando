<?php

namespace Nanbando\Storage;

use Symfony\Component\Finder\SplFileInfo;

class LocalStorage
{
    /**
     * @var string
     */
    private $localDirectory;

    /**
     * @var LocalFinderFactory
     */
    private $finderFactory;

    public function __construct(string $localDirectory, LocalFinderFactory $finderFactory)
    {
        $this->localDirectory = $localDirectory;
        $this->finderFactory = $finderFactory;
    }

    /**
     * @return ArchiveInfo[]
     */
    public function listFiles(): array
    {
        $result = [];

        /** @var SplFileInfo $file */
        foreach ($this->finderFactory->create() as $file) {
            $name = explode('.', $file->getFilename())[0];
            if (!array_key_exists($name, $result)) {
                $result[$name] = $this->get($name);
            }
        }

        return $result;
    }

    public function get(string $name): ArchiveInfo
    {
        return new ArchiveInfo(
            $name,
            sprintf('%s/%s.tar.gz', $this->localDirectory, $name),
            sprintf('%s/%s.json', $this->localDirectory, $name),
            $this->exists($name)
        );
    }

    public function exists(string $name): bool
    {
        return $this->finderFactory->create()->name($name . '.tar.gz')->count() > 0;
    }

    public function size()
    {
        $size = 0;
        foreach ($this->finderFactory->create() as $file) {
            $size += $file->getSize();
        }

        return $size;
    }
}
