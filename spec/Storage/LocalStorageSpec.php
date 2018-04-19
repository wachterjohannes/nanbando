<?php

namespace spec\Nanbando\Storage;

use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalFinderFactory;
use Nanbando\Storage\LocalStorage;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

class LocalStorageSpec extends ObjectBehavior
{
    public function let(
        LocalFinderFactory $finderFactory
    ) {
        $this->beConstructedWith('/tmp/backups', $finderFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LocalStorage::class);
    }

    public function it_should_return_list_of_archives(
        LocalFinderFactory $finderFactory,
        Finder $finder,
        \SplFileInfo $file1,
        \SplFileInfo $file2
    ) {
        $finderFactory->create()->willReturn($finder);
        $finder->getIterator()->willReturn(new \ArrayObject([$file1->getWrappedObject(), $file2->getWrappedObject()]));

        $file1->getFilename()->willReturn('20180415-202700.tar.gz');
        $file2->getFilename()->willReturn('20180415-202700.json');

        $finderFactory->create()->willReturn($finder);

        $finder->name('20180415-202700.tar.gz')->willReturn($finder)->shouldBeCalled();
        $finder->count()->willReturn(true)->shouldBeCalled();

        $this->listFiles()->shouldBeArchiveInfos(['20180415-202700']);
    }

    public function it_should_return_archive_info(
        LocalFinderFactory $finderFactory,
        Finder $finder
    ) {
        $finderFactory->create()->willReturn($finder);

        $finder->name('20180415-202700.tar.gz')->willReturn($finder)->shouldBeCalled();
        $finder->count()->willReturn(true)->shouldBeCalled();

        $this->get('20180415-202700')->shouldBeArchiveInfo('20180415-202700', true);
    }

    public function it_should_return_true_if_archive_exists(
        LocalFinderFactory $finderFactory,
        Finder $finder
    ) {
        $finderFactory->create()->willReturn($finder);

        $finder->name('20180415-202700.tar.gz')->willReturn($finder)->shouldBeCalled();
        $finder->count()->willReturn(true)->shouldBeCalled();

        $this->exists('20180415-202700')->shouldBe(true);
    }

    public function getMatchers(): array
    {
        return [
            'beArchiveInfo' => function (ArchiveInfo $subject, string $name, bool $fetched) {
                Assert::eq($subject->getName(), $name);
                Assert::eq($subject->isFetched(), $fetched);

                Assert::eq($subject->getDatabaseName(), $name . '.json');
                Assert::eq($subject->getArchiveName(), $name . '.tar.gz');

                Assert::eq($subject->getDatabasePath(), '/tmp/backups/' . $name . '.json');
                Assert::eq($subject->getArchivePath(), '/tmp/backups/' . $name . '.tar.gz');

                return true;
            },
            'beArchiveInfos' => function (array $archiveInfos, array $names) {
                $archiveInfos = array_values($archiveInfos);
                for ($i = 0; $i < count($archiveInfos); ++$i) {
                    Assert::eq($archiveInfos[$i]->getName(), $names[$i]);
                }

                return true;
            },
        ];
    }
}
