<?php

namespace Nanbando\Backup;

class BackupArchiveDecorator implements BackupArchiveInterface
{
    /**
     * @var BackupArchiveInterface
     */
    private $innerBackupArchive;

    /**
     * @var string
     */
    private $scriptName;

    public function __construct(string $scriptName, BackupArchiveInterface $innerBackupArchive)
    {
        $this->scriptName = $scriptName;
        $this->innerBackupArchive = $innerBackupArchive;
    }

    public function storeFile(string $name, string $path): void
    {
        $this->innerBackupArchive->storeFile(sprintf('%s/%s', $this->scriptName, $name), $path);
    }

    public function getFiles(): array
    {
        return $this->innerBackupArchive->getFiles();
    }

    public function set(string $name, $value): void
    {
        $this->innerBackupArchive->set(sprintf('%s.%s', $this->scriptName, $name), $value);
    }

    public function get(string $name)
    {
        return $this->innerBackupArchive->get(sprintf('%s.%s', $this->scriptName, $name));
    }

    public function getWithDefault(string $name, $default)
    {
        return $this->innerBackupArchive->getWithDefault(sprintf('%s.%s', $this->scriptName, $name), $default);
    }

    public function all(): array
    {
        $result = [];
        foreach ($this->innerBackupArchive->all() as $key => $value) {
            $prefix = $this->scriptName . '.';
            if ($prefix === substr($key, 0, strlen($prefix))) {
                $result[substr($key, strlen($prefix))] = $value;
            }
        }

        return $result;
    }
}
