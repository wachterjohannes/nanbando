<?php

namespace spec\Nanbando\Console;

use Nanbando\Console\Application;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Application as SymfonyApplication;

class ApplicationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Application::class);
    }

    public function it_extends_symfony_application()
    {
        $this->shouldBeAnInstanceOf(SymfonyApplication::class);
    }
}
