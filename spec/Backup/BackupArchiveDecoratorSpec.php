<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchiveDecorator;
use Nanbando\Backup\BackupArchiveInterface;
use PhpSpec\ObjectBehavior;

class BackupArchiveDecoratorSpec extends ObjectBehavior
{
    public function let(
        BackupArchiveInterface $backupArchive
    ) {
        $this->beConstructedWith('test', $backupArchive);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupArchiveDecorator::class);
    }

    public function it_should_implement_backup_archive()
    {
        $this->shouldBeAnInstanceOf(BackupArchiveInterface::class);
    }

    public function it_should_add_script_name_on_store_file(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->storeFile('test/test.json', '/var/test.json', null)->shouldBeCalled();

        $this->storeFile('test.json', '/var/test.json');
    }

    public function it_should_add_script_name_on_store_file_pass_given_metadata(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->storeFile('test/test.json', '/var/test.json', ['hash' => '123456'])->shouldBeCalled();

        $this->storeFile('test.json', '/var/test.json', ['hash' => '123456']);
    }

    public function it_should_add_script_name_on_store_metadata(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->storeMetadata('test/test.json', ['hash' => '123456'])->shouldBeCalled();

        $this->storeMetadata('test.json', ['hash' => '123456']);
    }

    public function it_should_add_script_name_on_set_parameter(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->set('test.parameter', 'value')->shouldBeCalled();

        $this->set('parameter', 'value');
    }

    public function it_should_add_script_name_on_get_parameter(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->get('test.parameter')->shouldBeCalled()->willReturn('value');

        $this->get('parameter')->shouldBe('value');
    }

    public function it_should_add_script_name_on_get_with_default_parameter(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->getWithDefault('test.parameter', 'default')->shouldBeCalled()->willReturn('value');

        $this->getWithDefault('parameter', 'default')->shouldBe('value');
    }

    public function it_should_remove_script_name(
        BackupArchiveInterface $backupArchive
    ) {
        $backupArchive->all()->willReturn(['test.parameter' => 'value', 'test1.parameter' => 'value2']);

        $this->all()->shouldBeEqualTo(['parameter' => 'value']);
    }
}
