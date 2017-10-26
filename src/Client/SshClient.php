<?php

namespace Nanbando\Client;

use Nanbando\Host\HostInterface;
use Nanbando\Process\ProcessFactory;

/**
 * Inspired by deployer/deployer.
 */
class SshClient implements ClientInterface
{
    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * @var HostInterface
     */
    private $host;

    /**
     * @var int
     */
    private $timeout = 300;

    public function __construct(ProcessFactory $processFactory, HostInterface $host, int $timeout)
    {
        $this->processFactory = $processFactory;
        $this->host = $host;
        $this->timeout = $timeout;
    }

    public function run(string $command, array $config = []): string
    {
        $workingDirectory = $this->host->getDirectory();
        if ($workingDirectory) {
            $command = sprintf('cd %s; %s', $workingDirectory, $command);
        }

        $config = array_merge(['tty' => true], $config);

        $fullHost = ($this->host->getUser() ? $this->host->getUser() . '@' : '') . $this->host->getHostname();

        $sshArguments = $this->getSshArguments();
        $sshArguments = $sshArguments->withFlag('-tt');

        $command = escapeshellarg($command);
        $ssh = "ssh $sshArguments $fullHost $command";
        $process = $this->processFactory->create($ssh)
            ->setTimeout($this->timeout)
            ->setTty($config['tty'])
            ->mustRun();

        return $process->getOutput();
    }

    private function getSshArguments(): SshArguments
    {
        return (new SshArguments())->withFlag('-p', $this->host->getPort());
    }
}
