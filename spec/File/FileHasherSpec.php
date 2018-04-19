<?php

namespace spec\Nanbando\File;

use Nanbando\File\FileHasher;
use PhpSpec\ObjectBehavior;
use VirtualFileSystem\FileSystem;
use VirtualFileSystem\Loader;

class FileHasherSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FileHasher::class);
    }

    public function it_should_hash_file()
    {
        $content = '{"attribute": "value"}';

        $l = new Loader();
        $l->register();

        $fs = new FileSystem();
        file_put_contents($fs->path('test.json'), $content);

        $this->hash($fs->path('test.json'))->shouldBe(hash('sha224', $content));
    }
}
