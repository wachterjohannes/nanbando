<?php

namespace spec\Nanbando\Script;

use Nanbando\Script\ScriptInterface;
use Nanbando\Script\ScriptRegistry;
use PhpSpec\ObjectBehavior;

class ScriptRegistrySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ScriptRegistry::class);
    }

    public function it_should_return_scripts(
        ScriptInterface $script
    ) {
        $this->beConstructedWith(['test' => $script]);

        $this->get()->shouldBe(['test' => $script]);
    }
}
