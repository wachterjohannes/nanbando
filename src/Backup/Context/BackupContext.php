<?php

namespace Nanbando\Backup\Context;

use Nanbando\Filesystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class BackupContext
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var self
     */
    protected $parent;

    /**
     * @var ParameterBag
     */
    protected $parameterBag;

    public function __construct(FilesystemInterface $filesystem, ?string $name = null, ?BackupContext $parent = null)
    {
        $this->filesystem = $filesystem;
        $this->name = $name;
        $this->parent = $parent;

        $this->parameterBag = new ParameterBag();
    }

    public function set(string $name, $value): self
    {
        $this->parameterBag->set($this->prefixName($name), $value);

        return $this;
    }

    public function get(string $name)
    {
        return $this->parameterBag->get($this->prefixName($name));
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function open(string $name): self
    {
        return new self($this->filesystem->decorate($this->prefixName($name, '/')), $this->prefixName($name), $this);
    }

    public function close(): ?self
    {
        if (!$this->parent) {
            $this->filesystem->addContent(json_encode($this->parameterBag->all(), JSON_PRETTY_PRINT), 'database.json');
            $this->filesystem->close();

            return null;
        }

        $this->parent->parameterBag->add($this->parameterBag->all());

        return $this->parent;
    }

    private function prefixName(string $name, string $glue = '.'): string
    {
        return implode($glue, array_filter([$this->name, $name]));
    }
}
