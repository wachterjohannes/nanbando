<?php

namespace Nanbando\Tests\Unit\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Filesystem\FilesystemInterface;
use Nanbando\Plugin\MysqlDumpPlugin;
use Nanbando\Process\ProcessFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Process\Process;

class MysqlDumpPluginTest extends TestCase
{
    public function provideData()
    {
        return [
            [
                [
                    'database' => 'su_minimal',
                    'username' => 'root',
                    'password' => 'secret',
                    'host' => '127.0.0.1',
                    'port' => 3306,
                ],
                'mysqldump -uroot -p\'secret\' -h 127.0.0.1 -P 3306 su_minimal > ' . __DIR__ . '/temp.txt',
            ],
            [
                [
                    'database' => 'su_minimal',
                    'username' => 'root',
                    'host' => '127.0.0.1',
                    'port' => 3306,
                ],
                'mysqldump -uroot -h 127.0.0.1 -P 3306 su_minimal > ' . __DIR__ . '/temp.txt',
            ],
            [
                [
                    'database' => 'su_minimal',
                    'username' => 'root',
                    'port' => 3306,
                ],
                'mysqldump -uroot -P 3306 su_minimal > ' . __DIR__ . '/temp.txt',
            ],
            [
                [
                    'database' => 'su_minimal',
                    'username' => 'root',
                ],
                'mysqldump -uroot su_minimal > ' . __DIR__ . '/temp.txt',
            ],
        ];
    }

    /**
     * @dataProvider provideData
     */
    public function testBackup(array $options, string $command)
    {
        $processFactory = $this->prophesize(ProcessFactory::class);
        $context = $this->prophesize(BackupContext::class);
        $filesystem = $this->prophesize(FilesystemInterface::class);
        $process = $this->prophesize(Process::class);

        $context->getFilesystem()->willReturn($filesystem->reveal());

        $tempFilename = __DIR__ . '/temp.txt';
        file_put_contents($tempFilename, 'test');
        $filesystem->tempFilename()->willReturn($tempFilename);

        $processFactory->create($command)->willReturn($process->reveal());
        $process->start()->shouldBeCalled();
        $process->isRunning()->willReturn(false);

        $filesystem->addFile($tempFilename, 'dump.sql')->shouldBeCalled()->willReturn($filesystem->reveal());

        $plugin = new MysqlDumpPlugin($processFactory->reveal(), $options);

        $plugin->backup($context->reveal(), new ArgvInput(), new NullOutput());

        $this->assertFileNotExists($tempFilename);
    }
}
