<?php

namespace spec\Nanbando\TempFileManager;

use Nanbando\Console\SectionOutputFormatter;
use Nanbando\TempFileManager\TempFileManager;
use Nanbando\TempFileManager\TempFileManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

class TempFileManagerSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem
    ) {
        $this->beConstructedWith($filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TempFileManager::class);
    }

    public function it_should_implement_temp_file_manager()
    {
        $this->shouldBeAnInstanceOf(TempFileManagerInterface::class);
    }

    public function it_should_return_temp_name(
        Filesystem $filesystem
    ) {
        $filesystem->tempnam(Argument::cetera())->willReturn('/tmp/nanbando1');

        $this->create()->shouldEqual('/tmp/nanbando1');
    }

    public function it_should_cleanup_all_temp_files(
        Filesystem $filesystem,
        SectionOutputFormatter $output
    ) {
        $output->progressBar(Argument::any())->willReturn(new ProgressBar(new NullOutput()));

        $filesystem->tempnam(Argument::cetera())->willReturn('/tmp/nanbando1');
        $this->create();

        $filesystem->remove('/tmp/nanbando1')->shouldBeCalled();

        $this->cleanup($output);
    }
}
