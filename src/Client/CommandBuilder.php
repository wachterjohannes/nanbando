<?php

namespace Nanbando\Client;

class CommandBuilder
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $options = [];

    public function __construct(string $command, array $arguments, array $options)
    {
        $this->command = $command;
        $this->arguments = $arguments;
        $this->options = $options;
    }

    public function __toString()
    {
        $tokens = array_merge([$this->command], $this->arguments);
        foreach (array_filter($this->options) as $key => $value) {
            if ($value === true) {
                $tokens[] = sprintf('--%s', $key);

                continue;
            }

            $tokens[] = sprintf('--%s="%s"', $key, $value);
        }

        return implode(' ', $tokens);
    }
}
