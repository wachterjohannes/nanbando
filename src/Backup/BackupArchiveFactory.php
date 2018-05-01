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
        $backupArchive = new BackupArchive($this->metadataFactory);

        $backupArchive->set('mode', BackupArchiveInterface::BACKUP_MODE_FULL);
        $backupArchive->set('parent', null);

        return $backupArchive;
    }

    public function createDifferential(string $parent): BackupArchiveInterface
    {
        $parentInfo = $this->localStorage->get($parent);
        $parentDatabase = $parentInfo->openDatabase();
        if (BackupArchiveInterface::BACKUP_MODE_FULL !== $parentDatabase->get('mode')) {
            throw new \RuntimeException('Parent for diffenrential backup has to be a full backup.');
        }

        $backupArchive = new DifferentialBackupArchive($parentDatabase, $this->metadataFactory, $this->create());

        $backupArchive->set('mode', BackupArchiveInterface::BACKUP_MODE_DIFFERENTIAL);
        $backupArchive->set('parent', $parent);

        return $backupArchive;
    }
}
