<?php

namespace Nanbando\Backup;

use Nanbando\File\MetadataFactory;
use Nanbando\Storage\LocalStorage;

class BackupArchiveFactory
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var LocalStorage
     */
    private $localStorage;

    public function __construct(MetadataFactory $metadataFactory, LocalStorage $localStorage)
    {
        $this->metadataFactory = $metadataFactory;
        $this->localStorage = $localStorage;
    }

    public function create(): BackupArchiveInterface
    {
        return new BackupArchive($this->metadataFactory);
    }

    public function createDifferential(string $parent): BackupArchiveInterface
    {
        $parentInfo = $this->localStorage->get($parent);
        $parentDatabase = $parentInfo->openDatabase();

        $backupArchive = new DifferentialBackupArchive($parentDatabase, $this->metadataFactory, $this->create());

        $backupArchive->set('mode', BackupArchiveInterface::BACKUP_MODE_DIFFERENTIAL);
        $backupArchive->set('parent', $parent);

        return $backupArchive;
    }
}
