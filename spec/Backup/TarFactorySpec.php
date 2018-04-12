<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\TarFactory;
use PhpSpec\ObjectBehavior;
use splitbrain\PHPArchive\Tar;

class TarFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TarFactory::class);
    }

    public function it_should_return_tar()
    {
        $this->create()->shouldBeAnInstanceOf(Tar::class);
    }
}
