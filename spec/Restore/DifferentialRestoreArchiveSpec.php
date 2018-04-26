<?php

namespace spec\Nanbando\Restore;

use Nanbando\Restore\DifferentialRestoreArchive;
use Nanbando\Restore\RestoreArchiveInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DifferentialRestoreArchiveSpec extends ObjectBehavior
{
    public function let(
        RestoreArchiveInterface $restoreArchive,
        RestoreArchiveInterface $parentArchive
    ) {
        $this->beConstructedWith($restoreArchive, $parentArchive);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DifferentialRestoreArchive::class);
    }

    public function it_should_implement_restore_archive_interface()
    {
        $this->shouldBeAnInstanceOf(RestoreArchiveInterface::class);
    }

    public function it_should_return_metadata(
        RestoreArchiveInterface $restoreArchive
    ) {
        $restoreArchive->getMetadata()->willReturn(['database.json' => []])->shouldBeCalled();

        $this->getMetadata()->shouldBe(['database.json' => []]);
    }

    public function it_should_return_parameter(
        RestoreArchiveInterface $restoreArchive
    ) {
        $restoreArchive->get('attribute')->willReturn('value')->shouldBeCalled();

        $this->get('attribute')->shouldBe('value');
    }

    public function it_should_return_parameter_with_default(
        RestoreArchiveInterface $restoreArchive
    ) {
        $restoreArchive->getWithDefault('attribute', 'default')->willReturn('value')->shouldBeCalled();

        $this->getWithDefault('attribute', 'default')->shouldBe('value');
    }

    public function it_should_close_both_archives(
        RestoreArchiveInterface $restoreArchive,
        RestoreArchiveInterface $parentArchive
    ) {
        $restoreArchive->close()->shouldBeCalled();
        $parentArchive->close()->shouldBeCalled();

        $this->close();
    }

    public function it_should_fetch_file(
        RestoreArchiveInterface $restoreArchive
    ) {
        $restoreArchive->getMetadata()->willReturn(['database.json' => []])->shouldBeCalled();

        $restoreArchive->fetchFile('database.json', '/tmp/database.json')->shouldBeCalled();

        $this->fetchFile('database.json', '/tmp/database.json');
    }

    public function it_should_throw_exception_on_fetch_file(
        RestoreArchiveInterface $restoreArchive,
        RestoreArchiveInterface $parentArchive
    ) {
        $restoreArchive->getMetadata()->willReturn([])->shouldBeCalled();

        $restoreArchive->fetchFile(Argument::cetera())->shouldNotBeCalled();
        $parentArchive->fetchFile(Argument::cetera())->shouldNotBeCalled();

        $this->shouldThrow(\RuntimeException::class)->during('fetchFile', ['database.json', '/tmp/database.json']);
    }

    public function it_should_on_fetch_file_from_parent(
        RestoreArchiveInterface $restoreArchive,
        RestoreArchiveInterface $parentArchive
    ) {
        $restoreArchive->getMetadata()
            ->willReturn(['database.json' => ['archive' => '20180226-204200']])
            ->shouldBeCalled();

        $restoreArchive->fetchFile(Argument::cetera())->shouldNotBeCalled();
        $parentArchive->fetchFile('database.json', '/tmp/database.json')->shouldBeCalled();

        $this->fetchFile('database.json', '/tmp/database.json');
    }
}
