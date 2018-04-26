<?php

namespace Nanbando\Backup;

use Nanbando\File\MetadataFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DifferentialBackupArchive implements BackupArchiveInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $parentDatabase;

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var BackupArchiveInterface
     */
    private $innerArchive;

    public function __construct(
        ParameterBagInterface $parentDatabase,
        MetadataFactory $metadataFactory,
        BackupArchiveInterface $innerArchive
    ) {
        $this->parentDatabase = $parentDatabase;
        $this->innerArchive = $innerArchive;
        $this->metadataFactory = $metadataFactory;
    }

    public function storeFile(string $name, string $path, ?array $metadata = null): void
    {
        $metadata = $metadata ?: $this->metadataFactory->create($path);
        if ($parentMetadata = $this->findParentMetadata($name, $metadata)) {
            $parentMetadata['archive'] = $this->parentDatabase->get('name');
            $this->innerArchive->storeMetadata($name, $parentMetadata);

            return;
        }

        $this->innerArchive->storeFile($name, $path, $metadata);
    }

    public function storeMetadata(string $name, array $metadata): void
    {
        $this->innerArchive->storeMetadata($name, $metadata);
    }

    public function getFiles(): array
    {
        return $this->innerArchive->getFiles();
    }

    public function set(string $name, $value): void
    {
        $this->innerArchive->set($name, $value);
    }

    public function get(string $name)
    {
        return $this->innerArchive->get($name);
    }

    public function getWithDefault(string $name, $default)
    {
        return $this->innerArchive->getWithDefault($name, $default);
    }

    public function all(): array
    {
        return $this->innerArchive->all();
    }

    protected function findParentMetadata(string $name, array $metadata): ?array
    {
        $parameterMetadata = $this->parentDatabase->get('metadata');
        if (!array_key_exists($name, $parameterMetadata)) {
            return null;
        }

        $parentMetadata = $parameterMetadata[$name];
        if ($metadata['hash'] !== $parentMetadata['hash']) {
            return null;
        }

        return $parentMetadata;
    }
}
