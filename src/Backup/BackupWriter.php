<?php

namespace Nanbando\Backup;

use Nanbando\Clock\Clock;
use Nanbando\Console\SectionOutputFormatter;
use splitbrain\PHPArchive\Archive;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class BackupWriter
{
    /**
     * @var string
     */
    private $localDirectory;

    /**
     * @var TarFactory
     */
    private $factory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Clock
     */
    private $clock;

    public function __construct(string $localDirectory, TarFactory $factory, Filesystem $filesystem, Clock $clock)
    {
        $this->localDirectory = $localDirectory;
        $this->factory = $factory;
        $this->filesystem = $filesystem;
        $this->clock = $clock;
    }

    public function write(BackupArchiveInterface $backupArchive, SectionOutputFormatter $output): string
    {
        if (!$this->filesystem->exists($this->localDirectory)) {
            $this->filesystem->mkdir($this->localDirectory);
        }

        $fileName = $this->clock->getDateTime()->format('Ymd-His');
        $label = $backupArchive->get('label');
        if ('' !== $label) {
            // TODO slugify label

            $fileName = sprintf('%s_%s', $fileName, $label);
        }

        $fileName .= '.tar.gz';

        $tar = $this->factory->create();
        $tar->setCompression(9, Archive::COMPRESS_AUTO);
        $tar->create(Path::join($this->localDirectory, $fileName));

        $progressBar = $output->progressBar(count($backupArchive->getFiles()) + 1);
        foreach ($backupArchive->getFiles() as $name => $file) {
            $progressBar->advance();
            $tar->addFile($file, $name);
        }

        $progressBar->advance();
        $tar->addData('database.json', json_encode($backupArchive->all()));
        $tar->close();

        $progressBar->finish();

        return $fileName;
    }
}
