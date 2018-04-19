<?php

namespace Nanbando\Restore;

use splitbrain\PHPArchive\Tar;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

class RestoreArchive implements RestoreArchiveInterface
{
    /**
     * @var string
     */
    private $archivePath;

    /**
     * @var Tar
     */
    private $tar;

    /**
     * @var ParameterBagInterface
     */
    private $database;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $archivePath, Tar $tar, ParameterBagInterface $database, Filesystem $filesystem)
    {
        $this->archivePath = $archivePath;
        $this->tar = $tar;
        $this->database = $database;
        $this->filesystem = $filesystem;
    }

    public function getMetadata(): array
    {
        return $this->database->get('metadata');
    }

    public function fetchFile(string $name, string $path): void
    {
        $dirname = dirname($path);
        $this->filesystem->mkdir($dirname);

        $this->tar->open($this->archivePath);
        $this->tar->extract(sys_get_temp_dir(), '', '', '/' . str_replace('/', '\/', preg_quote($name)) . '/');
        $this->filesystem->rename(Path::join(sys_get_temp_dir(), $name), Path::join($dirname, basename($name)));
    }

    public function get(string $name)
    {
        return $this->database->get($name);
    }

    public function getWithDefault(string $name, $default)
    {
        try {
            return $this->get($name);
        } catch (ParameterNotFoundException $exception) {
            return $default;
        }
    }

    public function close(): void
    {
        $this->tar->close();
    }
}
