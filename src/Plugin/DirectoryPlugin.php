<?php

namespace Nanbando\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

class DirectoryPlugin implements PluginInterface
{
    public static function create(string $directory): self
    {
        return new self($directory);
    }

    /**
     * @var string
     */
    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function backup(BackupContext $context, InputInterface $input, OutputInterface $output)
    {
        $filesystem = $context->getFilesystem();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory));

        $progressBar = new ProgressBar($output, iterator_count($files));
        $progressBar->setOverwrite(true);
        $progressBar->setFormat('  %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $progressBar->start();

        $metadata = [];

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $path = Path::makeRelative($file->getRealPath(), $this->directory);
            $metadata[$path] = $this->getMetadata($file);

            $filesystem->addFile($file->getRealPath(), $path);

            $progressBar->advance();
        }

        $context->set('metadata', $metadata);

        $progressBar->finish();
        $output->writeln('');
    }

    protected function getMetadata(\SplFileInfo $file): array
    {
        return [
            'filename' => $file->getFilename(),
            'type' => $file->getType(),
            'extension' => $file->getExtension(),
            'accessTime' => $file->getATime(),
            'creationTime' => $file->getCTime(),
            'modificationTime' => $file->getMTime(),
            'size' => $file->getSize(),
            'hash' => hash_file('sha224', $file->getRealPath()),
        ];
    }
}
