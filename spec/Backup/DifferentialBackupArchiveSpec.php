<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Backup\DifferentialBackupArchive;
use Nanbando\File\MetadataFactory;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DifferentialBackupArchiveSpec extends ObjectBehavior
{
    public function let(
        ParameterBagInterface $parameterBag,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $this->beConstructedWith($parameterBag, $metadataFactory, $innerArchive);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DifferentialBackupArchive::class);
    }

    public function it_should_store_file_if_not_exists(
        ParameterBagInterface $parameterBag,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $parameterBag->get('metadata')->willReturn(['test.txt' => ['hash' => '123456']]);
        $metadataFactory->create('/tmp/not-existing.txt')->willReturn(['hash' => 'other-hash']);

        $innerArchive->storeFile('not-existing.txt', '/tmp/not-existing.txt', ['hash' => 'other-hash'])->shouldBeCalled();

        $this->storeFile('not-existing.txt', '/tmp/not-existing.txt');
    }

    public function it_should_store_file_if_hash_changed(
        ParameterBagInterface $parameterBag,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $parameterBag->get('metadata')->willReturn(['test.txt' => ['hash' => '123456']]);
        $metadataFactory->create('/tmp/not-existing.txt')->willReturn(['hash' => 'other-hash']);

        $innerArchive->storeFile('test.txt', '/tmp/not-existing.txt', ['hash' => 'other-hash'])->shouldBeCalled();

        $this->storeFile('test.txt', '/tmp/not-existing.txt');
    }

    public function it_should_store_file_if_hash_changed_and_reuse_given_metadta(
        ParameterBagInterface $parameterBag,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $parameterBag->get('metadata')->willReturn(['test.txt' => ['hash' => '123456']]);
        $metadataFactory->create('/tmp/not-existing.txt')->shouldnotBeCalled();

        $innerArchive->storeFile('test.txt', '/tmp/not-existing.txt', ['hash' => 'other-hash'])->shouldBeCalled();

        $this->storeFile('test.txt', '/tmp/not-existing.txt', ['hash' => 'other-hash']);
    }

    public function it_should_only_store_metadata_for_unchanged_file(
        ParameterBagInterface $parameterBag,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $parameterBag->has('name')->willReturn(true);
        $parameterBag->get('name')->willReturn('20180422-121400');
        $parameterBag->has('metadata')->willReturn(true);
        $parameterBag->get('metadata')->willReturn(['test.txt' => ['hash' => '123456']]);
        $metadataFactory->create('/tmp/not-existing.txt')->willReturn(['hash' => '123456']);

        $innerArchive->storeMetadata('test.txt', ['hash' => '123456', 'archive' => '20180422-121400'])->shouldBeCalled();

        $this->storeFile('test.txt', '/tmp/not-existing.txt');
    }

    public function it_should_store_metadata(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->storeMetadata('database.json', ['hash' => '123456'])->shouldBeCalled();

        $this->storeMetadata('database.json', ['hash' => '123456']);
    }

    public function it_should_return_files(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->getFiles()->willReturn(['database.json' => ['hash' => '123456']]);

        $this->getFiles()->shouldBe(['database.json' => ['hash' => '123456']]);
    }

    public function it_should_set_parameter(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->set('attribute', 'value')->shouldBeCalled();

        $this->set('attribute', 'value');
    }

    public function it_should_get_parameter(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->get('attribute')->willReturn('value')->shouldBeCalled();

        $this->get('attribute')->shouldBe('value');
    }

    public function it_should_get_parameter_with_default(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->getWithDefault('attribute', 'default')->willReturn('value')->shouldBeCalled();

        $this->getWithDefault('attribute', 'default')->shouldBe('value');
    }

    public function it_should_get_all_parameter(
        BackupArchiveInterface $innerArchive
    ) {
        $innerArchive->all()->willReturn(['attribute' => 'value'])->shouldBeCalled();

        $this->all()->shouldBe(['attribute' => 'value']);
    }
}
