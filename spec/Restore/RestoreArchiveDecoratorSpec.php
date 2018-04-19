<?php

namespace spec\Nanbando\Restore;

use Nanbando\Restore\RestoreArchiveDecorator;
use Nanbando\Restore\RestoreArchiveInterface;
use PhpSpec\ObjectBehavior;

class RestoreArchiveDecoratorSpec extends ObjectBehavior
{
    public function let(
        RestoreArchiveInterface $innerArchive
    ) {
        $this->beConstructedWith('test', $innerArchive);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RestoreArchiveDecorator::class);
    }

    public function it_should_filter_metadata(
        RestoreArchiveInterface $innerArchive
    ) {
        $innerArchive->getMetadata()->willReturn(
            [
                'test/my-file.txt' => ['name' => 'my-file.txt'],
            ]
        );

        $this->getMetadata()->shouldBe(['my-file.txt' => ['name' => 'my-file.txt']]);
    }

    public function is_should_prefix_name_for_fetch_file(
        RestoreArchiveInterface $innerArchive
    ) {
        $innerArchive->fetchFile('test/my-file.txt', '/tmp/my-file.txt')->shouldBeCalled();

        $this->fetchFile('my-file.txt', '/tmp/my-file.txt');
    }

    public function it_should_prefix_name_for_get_parameter(
        RestoreArchiveInterface $innerArchive
    ) {
        $innerArchive->get('test.parameter')->willReturn('value')->shouldBecalled();

        $this->get('parameter')->shouldBe('value');
    }

    public function it_should_prefix_name_for_get_parameter_with_default(
        RestoreArchiveInterface $innerArchive
    ) {
        $innerArchive->getWithDefault('test.parameter', 'default')->willReturn('value')->shouldBecalled();

        $this->getWithDefault('parameter', 'default')->shouldBe('value');
    }

    public function it_should_close_archive(
        RestoreArchiveInterface $innerArchive
    ) {
        $innerArchive->close()->shouldBecalled();

        $this->close();
    }
}
