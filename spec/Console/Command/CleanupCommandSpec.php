<?php

namespace spec\Nanbando\Console\Command;

use Nanbando\Cleanup\Cleaner;
use Nanbando\Console\Command\CleanupCommand;
use Nanbando\Storage\LocalStorage;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class CleanupCommandSpec extends ObjectBehavior
{
    public function let(
        Cleaner $cleaner,
        LocalStorage $localStorage
    ) {
        $this->beConstructedWith($cleaner, $localStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CleanupCommand::class);
    }

    public function it_extends_symfony_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    // TODO more specs
}
