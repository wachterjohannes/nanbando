<?php

namespace Nanbando\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

class DirectoryPlugin implements PluginInterface
{
    public static function create(string $directory): self
    {
        return new DirectoryPlugin($directory);
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

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filesystem->addFile($file->getRealPath(), Path::makeRelative($file->getRealPath(), $this->directory));
        }
    }
}
