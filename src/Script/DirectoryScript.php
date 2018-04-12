<?php

namespace Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;
use Symfony\Component\Finder\Finder;
use Webmozart\PathUtil\Path;

class DirectoryScript implements ScriptInterface
{
    public static function create(string $directory)
    {
        return new self(Finder::create()->files()->in($directory), $directory);
    }

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $baseDirectory;

    public function __construct(Finder $finder, string $baseDirectory)
    {
        $this->finder = $finder;
        $this->baseDirectory = $baseDirectory;
    }

    public function backup(BackupArchiveInterface $backupArchive, SectionOutputFormatter $sectionOutput): void
    {
        $progressBar = $sectionOutput->progressBar($this->finder->count());

        /** @var \SplFileInfo $file */
        foreach ($this->finder as $file) {
            $progressBar->advance();

            if (!$file->isFile()) {
                continue;
            }

            $backupArchive->storeFile(
                Path::makeRelative($file->getRealPath(), $this->baseDirectory),
                $file->getRealPath()
            );
        }

        $progressBar->finish();
    }
}
