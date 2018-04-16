<?php

namespace spec\Nanbando\Initializer;

use Nanbando\Initializer\DirectoryScriptInitializer;
use PhpSpec\ObjectBehavior;

class DirectoryScriptInitializerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('/var/project');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryScriptInitializer::class);
    }

    public function it_should_return_correct_template()
    {
        $this->getTemplate(['name' => 'test', 'directory' => 'test-directory'])->shouldEqual(
            "attach('test', \Nanbando\Script\DirectoryScript::create(get('%cwd%/test-directory')));"
        );
    }
}
