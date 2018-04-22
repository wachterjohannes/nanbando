<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\ArchiveInfo;
use Nanbando\Tests\Behat\Assert;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use VirtualFileSystem\FileSystem;

class ArchiveInfoSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('20180422-115100', 'tmp/20180422-115100.tar.gz', 'tmp/20180422-115100.json', true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ArchiveInfo::class);
    }

    public function it_should_return_name()
    {
        $this->getName()->shouldBe('20180422-115100');
    }

    public function it_should_return_archive_path()
    {
        $this->getArchivePath()->shouldBe('tmp/20180422-115100.tar.gz');
    }

    public function it_should_return_archive_name()
    {
        $this->getArchiveName()->shouldBe('20180422-115100.tar.gz');
    }

    public function it_should_return_database_path()
    {
        $this->getDatabasePath()->shouldBe('tmp/20180422-115100.json');
    }

    public function it_should_return_database_name()
    {
        $this->getDatabaseName()->shouldBe('20180422-115100.json');
    }

    public function it_should_return_fetched()
    {
        $this->isFetched()->shouldBe(true);
    }

    public function it_should_open_database()
    {
        $content = '{"attribute": "value"}';

        $fs = new FileSystem();
        file_put_contents($fs->path('20180422-115100.json'), $content);

        $this->beConstructedWith('20180422-115100', $fs->path('20180422-115100.tar.gz'), $fs->path('20180422-115100.json'), true);

        Assert::true($this->openDatabase()->shouldBeAnInstanceOf(ParameterBagInterface::class)->has('attribute'));
    }
}
