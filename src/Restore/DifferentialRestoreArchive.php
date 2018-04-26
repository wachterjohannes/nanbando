<?php

namespace Nanbando\Restore;

class DifferentialRestoreArchive implements RestoreArchiveInterface
{
    /**
     * @var RestoreArchiveInterface
     */
    private $restoreArchive;

    /**
     * @var RestoreArchiveInterface
     */
    private $parent;

    public function __construct(RestoreArchiveInterface $restoreArchive, RestoreArchiveInterface $parent)
    {
        $this->restoreArchive = $restoreArchive;
        $this->parent = $parent;
    }

    public function getMetadata(): array
    {
        return $this->restoreArchive->getMetadata();
    }

    public function fetchFile(string $name, string $path): void
    {
        $metadata = $this->getMetadata();
        if (!array_key_exists($name, $metadata)) {
            throw new \RuntimeException();
        }

        $fileMetadata = $metadata[$name];
        if (array_key_exists('archive', $fileMetadata)) {
            $this->parent->fetchFile($name, $path);

            return;
        }

        $this->restoreArchive->fetchFile($name, $path);
    }

    public function get(string $name)
    {
        return $this->restoreArchive->get($name);
    }

    public function getWithDefault(string $name, $default)
    {
        return $this->restoreArchive->getWithDefault($name, $default);
    }

    public function close(): void
    {
        $this->restoreArchive->close();
        $this->parent->close();
    }
}
