<?php

namespace Nanbando\Backup;

use splitbrain\PHPArchive\Tar;

class TarFactory
{
    public function create(): Tar
    {
        return new Tar();
    }
}
