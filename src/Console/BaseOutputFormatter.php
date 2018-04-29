<?php

namespace Nanbando\Console;

use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseOutputFormatter
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $headlineCharacter;

    public function __construct(OutputInterface $output, string $headlineCharacter = '=')
    {
        $this->output = $output;
        $this->headlineCharacter = $headlineCharacter;
    }

    public function headline(string $format, ...$arguments): void
    {
        $line = sprintf($format, ...$this->formatValues($arguments));

        $this->output->writeln('');
        $this->output->writeln($line);
        $this->output->writeln(str_pad('', strlen($line), $this->headlineCharacter));
        $this->output->writeln('');
    }

    public function info(string $format, ...$arguments): void
    {
        $line = sprintf('<info>' . $format . '</info>', ...$this->formatValues($arguments));

        $this->output->writeln('');
        $this->output->writeln($line);
        $this->output->writeln('');
    }

    public function warning(string $format, ...$arguments): void
    {
        $line = sprintf('<comment>' . $format . '</comment>', ...$this->formatValues($arguments));

        $this->output->writeln('');
        $this->output->writeln($line);
        $this->output->writeln('');
    }

    public function list(array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            $this->output->writeln(sprintf(' * %s: %s', $key, $this->formatValue($value)));
        }

        $this->output->write(PHP_EOL);
    }

    protected function formatValues(array $arguments): array
    {
        foreach ($arguments as $key => $value) {
            $arguments[$key] = $this->formatValue($value);
        }

        return $arguments;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function formatValue($value)
    {
        if ($value instanceof \DateTime || $value instanceof \DateTimeImmutable) {
            return $value->format('Y-m-d H:i:s');
        } elseif (is_bool($value)) {
            return $value ? 'yes' : 'no';
        } elseif (is_array($value)) {
            $result = [];
            foreach ($value as $item) {
                $result[] = $this->formatValue($item);
            }

            return implode(', ', $result);
        } elseif (is_string($value)) {
            return sprintf('"%s"', $value);
        }

        return $value;
    }
}
