<?php

namespace Nanbando\Process;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    public function create(string $commandLine): Process
    {
        return new Process($commandLine);
    }
}
