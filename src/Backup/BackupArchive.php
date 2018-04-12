<?php

namespace Nanbando\Backup;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BackupArchive implements BackupArchiveInterface
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @var ParameterBagInterface
     */
    private $parameters;

    public function __construct(?ParameterBagInterface $parameterBag = null)
    {
        $this->parameters = $parameterBag ?: new ParameterBag();
    }

    public function storeFile(string $name, string $path): void
    {
        $this->files[$name] = $path;

        $metadata = $this->getWithDefault('metadata', []);
        $metadata[$name] = $this->getMetadata($path);
        $this->set('metadata', $metadata);
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

    protected function getMetadata(string $path): array
    {
        $file = new \SplFileInfo($path);

        return [
            'originalPath' => $path,
            'filename' => $file->getFilename(),
            'type' => $file->getType(),
            'extension' => $file->getExtension(),
            'accessTime' => $file->getATime(),
            'creationTime' => $file->getCTime(),
            'modificationTime' => $file->getMTime(),
            'size' => $file->getSize(),
            'hash' => hash_file('sha224', $file->getRealPath()),
        ];
    }
}
