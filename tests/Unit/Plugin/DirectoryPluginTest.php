<?php

namespace Nanbando\Tests\Unit\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Plugin\DirectoryPlugin;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

class DirectoryPluginTest extends TestCase
{
    public function testBackup()
    {
        $dirname = dirname(__DIR__);

        $filesystem = $this->prophesize(FilesystemInterface::class);
        $context = $this->prophesize(BackupContext::class);
        $context->getFilesystem()->willReturn($filesystem->reveal());

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirname));
        foreach ($files as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filesystem->addFile($file->getRealPath(), Path::makeRelative($file->getRealPath(), $dirname))
                ->shouldBeCalled()
                ->willReturn($filesystem->reveal());
        }

        $plugin = new DirectoryPlugin($dirname);
        $plugin->backup(
            $context->reveal(),
            $this->prophesize(InputInterface::class)->reveal(),
            $this->prophesize(OutputInterface::class)->reveal()
        );
    }
}
