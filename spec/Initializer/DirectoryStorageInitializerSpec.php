<?php

namespace spec\Nanbando\Initializer;

use Nanbando\Initializer\DirectoryStorageInitializer;
use PhpSpec\ObjectBehavior;

class DirectoryStorageInitializerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('/var/project');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DirectoryStorageInitializer::class);
    }

    public function it_should_return_correct_template()
    {
        $this->getTemplate(['name' => 'test', 'directory' => 'test-directory'])->shouldEqual(
            "storage('test', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/test-directory')));"
        );
    }
}
