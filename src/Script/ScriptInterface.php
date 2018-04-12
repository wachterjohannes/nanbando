<?php

namespace Nanbando\Script;

use Nanbando\Backup\BackupArchiveInterface;
use Nanbando\Console\SectionOutputFormatter;

interface ScriptInterface
{
    public function backup(BackupArchiveInterface $backupArchive, SectionOutputFormatter $sectionOutput): void;
}
