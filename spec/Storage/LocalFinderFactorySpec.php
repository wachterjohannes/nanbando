<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\LocalFinderFactory;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class LocalFinderFactorySpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem
    ) {
        $this->beConstructedWith('/tmp', $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LocalFinderFactory::class);
    }

    public function it_should_return_finder()
    {
        $this->create()->shouldBeAnInstanceOf(Finder::class);
    }

    public function it_should_ensure_that_the_directory_exists(
        Filesystem $filesystem
    ) {
        $filesystem->exists('/tmp')->wilLReturn(false);

        $filesystem->mkdir('/tmp')->shouldBeCalled();

        $this->create()->shouldBeAnInstanceOf(Finder::class);
    }
}
