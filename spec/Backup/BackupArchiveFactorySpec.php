<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchive;
use Nanbando\Backup\BackupArchiveFactory;
use Nanbando\Backup\DifferentialBackupArchive;
use Nanbando\File\MetadataFactory;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
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
    }

    public function it_should_return_differential_backup_archive(
        LocalStorage $localStorage,
        ArchiveInfo $archiveInfo,
        ParameterBagInterface $parameterBag
    ) {
        $localStorage->get('20180422-113200')->willReturn($archiveInfo)->shouldBeCalled();
        $archiveInfo->openDatabase()->willReturn($parameterBag)->shouldBeCalled();

        $this->createDifferential('20180422-113200')->shouldBeAnInstanceOf(DifferentialBackupArchive::class);
    }
}
