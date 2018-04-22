<?php

namespace spec\Nanbando\File;

use Nanbando\File\FileHasher;
use Nanbando\File\MetadataFactory;
use PhpSpec\ObjectBehavior;

class MetadataFactorySpec extends ObjectBehavior
{
    public function let(
        FileHasher $fileHasher
    ) {
        $this->beConstructedWith($fileHasher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MetadataFactory::class);
    }

    public function it_should_return_array_of_file_metadata(
        FileHasher $fileHasher
    ) {
        $filePath = __FILE__;
        $file = new \SplFileInfo($filePath);

        $fileHasher->hash($filePath)->shouldBeCalled()->willReturn('123456');

        $this->create($filePath)->shouldBe(
            [
                'originalPath' => $filePath,
                'filename' => $file->getFilename(),
                'type' => $file->getType(),
                'extension' => $file->getExtension(),
                'accessTime' => $file->getATime(),
                'creationTime' => $file->getCTime(),
                'modificationTime' => $file->getMTime(),
                'size' => $file->getSize(),
                'hash' => '123456',
            ]
        );
    }
}
