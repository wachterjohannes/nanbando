<?php

namespace Nanbando\Restore;

class RestoreArchiveDecorator implements RestoreArchiveInterface
{
    /**
     * @var string
     */
    private $scriptName;

    /**
     * @var RestoreArchiveInterface
     */
    private $innerArchive;

    public function __construct(string $scriptName, RestoreArchiveInterface $innerArchive)
    {
        $this->scriptName = $scriptName;
        $this->innerArchive = $innerArchive;
    }

    public function getMetadata(): array
    {
        $result = [];
        foreach ($this->innerArchive->getMetadata() as $name => $metadatum) {
            if (0 === strpos($name, $this->scriptName)) {
                $result[substr($name, strlen($this->scriptName) + 1)] = $metadatum;
            }
        }

        return $result;
    }

    public function fetchFile(string $name, string $path): void
    {
        $this->innerArchive->fetchFile(sprintf('%s/%s', $this->scriptName, $name), $path);
    }

    public function get(string $name)
    {
        return $this->innerArchive->get(sprintf('%s.%s', $this->scriptName, $name));
    }

    public function getWithDefault(string $name, $default)
    {
        return $this->innerArchive->getWithDefault(sprintf('%s.%s', $this->scriptName, $name), $default);
    }

    public function close(): void
    {
        $this->innerArchive->close();
    }
}
