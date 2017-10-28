<?php

namespace Nanbando\Tests\Mocks;

use Nanbando\Console\Application as NanbandoApplication;

class Application extends NanbandoApplication
{
    /**
     * @var string
     */
    private $process;

    public function mockProcess(string $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getProcess(): ?string
    {
        if ($this->process) {
            $process = $this->process;
            $this->process = null;

            return $process;
        }

        return parent::getProcess();
    }
}
