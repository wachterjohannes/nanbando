<?php

namespace Nanbando\Tests\Unit\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Plugin\DirectoryPlugin;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
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
        $fileNames = [];
        foreach ($files as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $fileNames[] = $filename = Path::makeRelative($file->getRealPath(), $dirname);
            $filesystem->addFile($file->getRealPath(), $filename)
                ->shouldBeCalled()
                ->willReturn($filesystem->reveal());
        }

        $context->set(
            'metadata',
            Argument::that(
                function (array $metadata) use ($fileNames) {
                    return $fileNames === array_keys($metadata);
                }
            )
        )->shouldBeCalled();

        $plugin = new DirectoryPlugin($dirname);
        $plugin->backup($context->reveal(), new ArgvInput(), new NullOutput());
    }
}
