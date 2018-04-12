<?php

namespace spec\Nanbando\Backup;

use Nanbando\Backup\BackupArchive;
use Nanbando\Backup\BackupArchiveInterface;
use PhpSpec\ObjectBehavior;

class BackupArchiveSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupArchive::class);
    }

    public function it_should_implement_backup_archive()
    {
        $this->shouldBeAnInstanceOf(BackupArchiveInterface::class);
    }

    // TODO more specs
}
