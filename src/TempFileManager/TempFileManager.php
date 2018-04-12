<?php

namespace Nanbando\TempFileManager;

use Nanbando\Console\SectionOutputFormatter;
use Symfony\Component\Filesystem\Filesystem;

class TempFileManager implements TempFileManagerInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $files = [];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function create(): string
    {
        return $this->files[] = $this->filesystem->tempnam(sys_get_temp_dir(), 'nanbando');
    }

    public function cleanup(SectionOutputFormatter $output): void
    {
        $progressBar = $output->progressBar(count($this->files));
        foreach ($this->files as $file) {
            $progressBar->advance();
            $this->filesystem->remove($file);
        }

        $this->files = [];

        $progressBar->finish();
    }
}
