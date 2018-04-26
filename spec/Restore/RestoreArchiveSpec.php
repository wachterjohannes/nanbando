<?php

namespace spec\Nanbando\Restore;

use Nanbando\Restore\RestoreArchive;
use Nanbando\Restore\RestoreArchiveInterface;
use PhpSpec\ObjectBehavior;
use splitbrain\PHPArchive\Tar;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class RestoreArchiveSpec extends ObjectBehavior
{
    public function let(
        Tar $tar,
        ParameterBagInterface $database,
        Filesystem $filesystem
    ) {
        $this->beConstructedWith('/tmp/20180419-162500.tar.gz', $tar, $database, $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreArchive::class);
    }

    public function it_should_implement_restore_archive_interface()
    {
        $this->shouldBeAnInstanceOf(RestoreArchiveInterface::class);
    }

    // TODO more specs
}
