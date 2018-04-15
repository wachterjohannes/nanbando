<?php

namespace Nanbando\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class LocalFinderFactory
{
    /**
     * @var string
     */
    private $localDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $localDirectory, Filesystem $filesystem)
    {
        $this->localDirectory = $localDirectory;
        $this->filesystem = $filesystem;
    }

    public function create(): Finder
    {
        if (!$this->filesystem->exists($this->localDirectory)) {
            $this->filesystem->mkdir($this->localDirectory);
        }

        return Finder::create()->files()->in($this->localDirectory)->sortByName();
    }
}
