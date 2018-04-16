<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\InitCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Console\SectionOutputFormatter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class InitCommandSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem,
        OutputFormatter $outputFormatter,
        InputInterface $input,
        SectionOutputFormatter $sectionOutputFormatter
    ) {
        $this->beConstructedWith(['script' => [], 'storage' => []], '/var/project', $filesystem, $outputFormatter);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);

        $outputFormatter->section()->willReturn($sectionOutputFormatter);
        $outputFormatter->headline(Argument::cetera())->should(
            function () {
                return true;
            }
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(InitCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_throw_exception_when_file_exists(
        Filesystem $filesystem,
        InputInterface $input,
        OutputInterface $output
    ) {
        $filesystem->exists('/var/project/backup.php')->willReturn(true);

        $this->shouldThrow(IOException::class)->duringRun($input, $output);
    }

    public function it_should_create_file_when_file_not_exists(
        Filesystem $filesystem,
        InputInterface $input,
        OutputInterface $output
    ) {
        $filesystem->exists('/var/project/backup.php')->willReturn(false);

        $filesystem->dumpFile('/var/project/backup.php', Argument::type('string'))->shouldBeCalled();

        $this->run($input, $output);
    }

    // TODO more specs
}
