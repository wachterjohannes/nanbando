<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchive;
use Nanbando\Backup\BackupArchiveFactory;
use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Backup\DifferentialBackupArchive;
use Nanbando\File\MetadataFactory;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BackupArchiveFactorySpec extends ObjectBehavior
{
    public function let(
        MetadataFactory $metadataFactory,
        LocalStorage $localStorage
    ) {
        $this->beConstructedWith($metadataFactory, $localStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupArchiveFactory::class);
    }

    public function it_should_return_backup_archive()
    {
        $this->create()->shouldBeAnInstanceOf(BackupArchive::class);
        $this->create()->shouldHaveParameter('mode', BackupArchiveInterface::BACKUP_MODE_FULL);
        $this->create()->shouldHaveParameter('parent', null);
    }

    public function it_should_return_differential_backup_archive(
        LocalStorage $localStorage,
        ArchiveInfo $archiveInfo,
        ParameterBagInterface $parameterBag
    ) {
        $localStorage->get('20180422-113200')->willReturn($archiveInfo)->shouldBeCalled();
        $archiveInfo->openDatabase()->willReturn($parameterBag)->shouldBeCalled();
        $parameterBag->get('mode')->willReturn(BackupArchiveInterface::BACKUP_MODE_FULL);

        $differentialMode = BackupArchiveInterface::BACKUP_MODE_DIFFERENTIAL;
        $this->createDifferential('20180422-113200')->shouldBeAnInstanceOf(DifferentialBackupArchive::class);
        $this->createDifferential('20180422-113200')->shouldHaveParameter('mode', $differentialMode);
        $this->createDifferential('20180422-113200')->shouldHaveParameter('parent', '20180422-113200');
    }

    public function it_should_throw_an_exception_for_wrong_parent_mode(
        LocalStorage $localStorage,
        ArchiveInfo $archiveInfo,
        ParameterBagInterface $parameterBag
    ) {
        $localStorage->get('20180422-113200')->willReturn($archiveInfo)->shouldBeCalled();
        $archiveInfo->openDatabase()->willReturn($parameterBag)->shouldBeCalled();
        $parameterBag->get('mode')->willReturn(BackupArchiveInterface::BACKUP_MODE_DIFFERENTIAL);

        $this->shouldThrow(\RuntimeException::class)->during('createDifferential', ['20180422-113200']);
    }

    public function getMatchers(): array
    {
        return [
            'haveParameter' => function (BackupArchiveInterface $subject, string $name, $value) {
                if ($subject->get($name) !== $value) {
                    throw new FailureException();
                }

                return true;
            },
        ];
    }
}
