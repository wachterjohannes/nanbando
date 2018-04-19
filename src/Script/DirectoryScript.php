<?php

namespace Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\File\FileHasher;
use Nanbando\Restore\RestoreArchiveInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Webmozart\PathUtil\Path;

class DirectoryScript implements ScriptInterface
{
    public static function create(string $directory)
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($directory);

        return new self(Finder::create()->files()->in($directory), $directory, $filesystem, new FileHasher());
    }

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $baseDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileHasher
     */
    private $fileHasher;

    public function __construct(Finder $finder, string $baseDirectory, Filesystem $filesystem, FileHasher $fileHasher)
    {
        $this->finder = $finder;
        $this->baseDirectory = $baseDirectory;
        $this->filesystem = $filesystem;
        $this->fileHasher = $fileHasher;
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

    public function restore(RestoreArchiveInterface $restoreArchive, SectionOutputFormatter $sectionOutput): void
    {
        $metadata = $restoreArchive->getMetadata();
        $progressBar = $sectionOutput->progressBar(count($metadata));

        foreach ($metadata as $name => $file) {
            $fullPath = Path::join($this->baseDirectory, $name);

            if ($this->filesystem->exists($fullPath)) {
                if ($this->fileHasher->hash($fullPath) === $file['hash']) {
                    $progressBar->advance();

                    continue;
                }

                $this->filesystem->remove($fullPath);
            }

            $restoreArchive->fetchFile($name, $fullPath);
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}
