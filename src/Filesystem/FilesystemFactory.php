<?php

namespace Nanbando\Filesystem;

use Nanbando\Nanbando;
use Webmozart\PathUtil\Path;

class FilesystemFactory
{
    public function create(string $label = ''): FilesystemInterface
    {
        $fileName = date('Ymd-Hi');
        if ($label !== '') {
            // TODO escape label
            $fileName = sprintf('%s_%s', $fileName, $label);
        }

        $fileName .= '.tar';

        // FIXME find better way to get live parameter
        $storage = Nanbando::get()->getParameterBag()->get('storage');
        $temp = Nanbando::get()->getParameterBag()->get('temp');

        $this->ensureDirectoryExists($storage);
        $this->ensureDirectoryExists($temp);

        $filePath = Path::join([$storage, $fileName]);
        $adapter = new PharDataAdapter(new \PharData($filePath), $filePath);

        return new Filesystem($adapter, $temp);
    }

    private function ensureDirectoryExists(string $directory)
    {
        if (is_dir($directory)) {
            return;
        }

        mkdir($directory, 0777, true);
    }
}
