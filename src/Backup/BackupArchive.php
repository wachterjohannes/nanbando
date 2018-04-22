<?php

namespace Nanbando\Backup;

use Nanbando\File\MetadataFactory;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BackupArchive implements BackupArchiveInterface
{
    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var ParameterBagInterface
     */
    private $parameters;

    public function __construct(MetadataFactory $metadataFactory, ?ParameterBagInterface $parameterBag = null)
    {
        $this->metadataFactory = $metadataFactory;
        $this->parameters = $parameterBag ?: new ParameterBag();
    }

    public function storeFile(string $name, string $path, ?array $metadata = null): void
    {
        $this->files[$name] = $path;

        $this->storeMetadata($name, $metadata ?: $this->metadataFactory->create($path));
    }

    public function storeMetadata(string $name, array $metadata): void
    {
        $databaseMetadata = $this->getWithDefault('metadata', []);
        $databaseMetadata[$name] = $metadata;
        $this->set('metadata', $databaseMetadata);
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function set(string $name, $value): void
    {
        $this->parameters->set($name, $value);
    }

    public function get(string $name)
    {
        return $this->parameters->get($name);
    }

    public function all(): array
    {
        return $this->parameters->all();
    }

    public function getWithDefault(string $name, $default)
    {
        try {
            return $this->get($name);
        } catch (ParameterNotFoundException $exception) {
            return $default;
        }
    }
}
