<?php

namespace Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;
use Nanbando\Restore\RestoreArchiveInterface;

interface ScriptInterface
{
    public function backup(BackupArchiveInterface $backupArchive, SectionOutputFormatter $sectionOutput): void;

    public function restore(RestoreArchiveInterface $restoreArchive, SectionOutputFormatter $sectionOutput): void;
}
