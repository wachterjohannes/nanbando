<?php

namespace Nanbando\TempFileManager;

use Nanbando\Console\SectionOutputFormatter;

interface TempFileManagerInterface
{
    public function create(): string;

    public function cleanup(SectionOutputFormatter $output): void;
}
