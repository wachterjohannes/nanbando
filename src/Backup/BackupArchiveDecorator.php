<?php

namespace Nanbando\Backup;

class BackupArchiveDecorator implements BackupArchiveInterface
{
    /**
     * @var BackupArchiveInterface
     */
    private $innerArchive;

    /**
     * @var string
     */
    private $scriptName;

    public function __construct(string $scriptName, BackupArchiveInterface $innerArchive)
    {
        $this->scriptName = $scriptName;
        $this->innerArchive = $innerArchive;
    }

    public function storeFile(string $name, string $path, ?array $metadata = null): void
    {
        $this->innerArchive->storeFile(sprintf('%s/%s', $this->scriptName, $name), $path, $metadata);
    }

    public function storeMetadata(string $name, array $metadata): void
    {
        $this->innerArchive->storeMetadata(sprintf('%s/%s', $this->scriptName, $name), $metadata);
    }

    public function getFiles(): array
    {
        return $this->innerArchive->getFiles();
    }

    public function set(string $name, $value): void
    {
        $this->innerArchive->set(sprintf('%s.%s', $this->scriptName, $name), $value);
    }

    public function get(string $name)
    {
        return $this->innerArchive->get(sprintf('%s.%s', $this->scriptName, $name));
    }

    public function getWithDefault(string $name, $default)
    {
        return $this->innerArchive->getWithDefault(sprintf('%s.%s', $this->scriptName, $name), $default);
    }

    public function all(): array
    {
        $result = [];
        foreach ($this->innerArchive->all() as $key => $value) {
            $prefix = $this->scriptName . '.';
            if ($prefix === substr($key, 0, strlen($prefix))) {
                $result[substr($key, strlen($prefix))] = $value;
            }
        }

        return $result;
    }
}
