<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchive;
use Nanbando\Backup\BackupWriter;
use Nanbando\Backup\TarFactory;
use Nanbando\Clock\Clock;
use Nanbando\Console\SectionOutputFormatter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

class BackupWriterSpec extends ObjectBehavior
{
    public function let(
        TarFactory $factory,
        Filesystem $filesystem,
        Clock $clock,
        Tar $tar,
        SectionOutputFormatter $outputFormatter
    ) {
        $outputFormatter->progressBar(Argument::any())->willReturn(new ProgressBar(new NullOutput()));

        $factory->create()->willReturn($tar);
        $clock->getDateTime()->willReturn(new \DateTimeImmutable('2018-04-05 20:20'));

        $this->beConstructedWith('/tmp', $factory, $filesystem, $clock);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupWriter::class);
    }

    public function it_should_store_all_files_in_tar_file(
        Filesystem $filesystem,
        Tar $tar,
        BackupArchive $backupArchive,
        SectionOutputFormatter $outputFormatter
    ) {
        $filesystem->exists('/tmp')->willReturn(true);
        $backupArchive->get('label')->willReturn('');
        $backupArchive->getFiles()->willReturn(
            [
                'uploads/test1.txt' => '/tmp/test1.txt',
                'uploads/test2.txt' => '/tmp/test2.txt',
            ]
        );
        $backupArchive->all()->willReturn(['key1' => 'value1', 'key2' => 'value2']);

        $tar->create('/tmp/20180405-202000.tar.gz')->shouldBeCalled();
        $tar->setCompression(9, -1)->shouldBeCalled();
        $tar->addFile('/tmp/test1.txt', 'uploads/test1.txt')->shouldBeCalled();
        $tar->addFile('/tmp/test2.txt', 'uploads/test2.txt')->shouldBeCalled();
        $tar->addData('database.json', json_encode(['key1' => 'value1', 'key2' => 'value2']))->shouldBeCalled();
        $tar->close()->shouldBeCalled();

        $this->write($backupArchive, $outputFormatter)->shouldEqual('20180405-202000.tar.gz');
    }

    public function it_should_create_folder_if_not_exists(
        Filesystem $filesystem,
        Tar $tar,
        BackupArchive $backupArchive,
        SectionOutputFormatter $outputFormatter
    ) {
        $backupArchive->get('label')->willReturn('');
        $backupArchive->getFiles()->willReturn([]);
        $backupArchive->all()->willReturn([]);

        $filesystem->exists('/tmp')->willReturn(false);
        $filesystem->mkdir('/tmp')->shouldBeCalled();

        $tar->create('/tmp/20180405-202000.tar.gz')->shouldBeCalled();
        $tar->setCompression(9, -1)->shouldBeCalled();
        $tar->addFile(Argument::cetera())->shouldNotBeCalled();
        $tar->addData('database.json', json_encode([]))->shouldBeCalled();
        $tar->close()->shouldBeCalled();

        $this->write($backupArchive, $outputFormatter);
    }

    public function it_should_add_tag_to_filename(
        Filesystem $filesystem,
        Tar $tar,
        BackupArchive $backupArchive,
        SectionOutputFormatter $outputFormatter
    ) {
        $filesystem->exists('/tmp')->willReturn(true);
        $backupArchive->getFiles()->willReturn([]);
        $backupArchive->all()->willReturn([]);

        $backupArchive->get('label')->willReturn('testtag');

        $tar->create('/tmp/20180405-202000_testtag.tar.gz')->shouldBeCalled();
        $tar->setCompression(9, -1)->shouldBeCalled();
        $tar->addFile(Argument::cetera())->shouldNotBeCalled();
        $tar->addData('database.json', json_encode([]))->shouldBeCalled();
        $tar->close()->shouldBeCalled();

        $this->write($backupArchive, $outputFormatter);
    }
}
