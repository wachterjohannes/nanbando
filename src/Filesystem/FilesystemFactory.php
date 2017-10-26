<?php

namespace Nanbando\Filesystem;

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

        return new PharDataFilesystem(new \PharData($fileName), $fileName);
    }
}
