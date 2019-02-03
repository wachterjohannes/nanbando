<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Console\Command\ListCommand;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ListCommandSpec extends ObjectBehavior
{
    public function let(
        Storage $storage,
        OutputFormatter $outputFormatter,
        InputInterface $input
    ) {
        $this->beConstructedWith($storage, $outputFormatter);

        $input->bind(Argument::cetera())->willReturn(null);
        $input->isInteractive()->willReturn(null);
        $input->hasArgument(Argument::cetera())->willReturn(null);
        $input->validate()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ListCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_list_local_files(
        InputInterface $input,
        OutputInterface $output,
        Storage $storage,
        OutputFormatter $outputFormatter,
        ArchiveInfo $archiveInfo1,
        ParameterBag $database1,
        ArchiveInfo $archiveInfo2,
        ParameterBag $database2
    ) {
        $storage->listFiles()->shouldBeCalled()->willReturn([$archiveInfo1, $archiveInfo2]);

        $archiveInfo1->getName()->willReturn('20190101-120000');
        $archiveInfo1->openDatabase()->willReturn($database1);
        $archiveInfo1->isFetched()->willReturn(false);
        $archiveInfo2->getName()->willReturn('20190201-120000');
        $archiveInfo2->openDatabase()->willReturn($database2);
        $archiveInfo2->isFetched()->willReturn(true);

        $storage->fetchDatabase($archiveInfo1)->shouldBeCalled();

        $database1->get('label')->willReturn('Label 1');
        $database2->get('label')->willReturn('Label 2');

        $database1->get('message')->willReturn('Message 1');
        $database2->get('message')->willReturn('Message 2');

        $database1->get('message')->willReturn('Message 1');
        $database2->get('message')->willReturn('Message 2');

        $database1->get('started')->willReturn('2019-01-01 12:00');
        $database2->get('started')->willReturn('2019-02-01 12:00');

        $database1->get('finished')->willReturn('2019-01-01 13:00');
        $database2->get('finished')->willReturn('2019-02-01 13:00');

        $this->run($input, $output);

        $outputFormatter->list(
            [
                'label' => 'Label 1',
                'message' => 'Message 1',
                'started' => '2019-01-01 12:00',
                'finished' => '2019-01-01 13:00',
            ]
        )->shouldBeCalled();

        $outputFormatter->list(
            [
                'label' => 'Label 2',
                'message' => 'Message 2',
                'started' => '2019-02-01 12:00',
                'finished' => '2019-02-01 13:00',
            ]
        )->shouldBeCalled();
    }
}
