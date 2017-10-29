<?php

namespace Nanbando\Plugin;

use Nanbando\Backup\Context\BackupContext;
use Nanbando\Nanbando;
use Nanbando\Process\ProcessFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MysqlDumpPlugin implements PluginInterface
{
    public static function create(
        string $database,
        string $username = 'root',
        ?string $password = null,
        ?string $host = '127.0.0.1',
        ?int $port = 3306
    ): self {
        return new self(
            Nanbando::get()->getService(ProcessFactory::class),
            [
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'host' => $host,
                'port' => $port,
            ]
        );
    }

    public static function autoConfigure(ParameterBag $parameter): self
    {
        if ($driver = 'pdo_mysql' !== $parameter->get('database_driver')) {
            throw new \Exception(sprintf('Driver "%s" is not supported by this plugin', $driver));
        }

        return static::create(
            $parameter->get('database_name'),
            $parameter->get('database_user'),
            $parameter->get('database_password'),
            $parameter->get('database_host'),
            $parameter->get('database_port')
        );
    }

    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * @var array
     */
    private $options = [];

    public function __construct(ProcessFactory $processFactory, array $options)
    {
        $this->processFactory = $processFactory;
        $this->options = array_merge(
            [
                'password' => null,
                'host' => null,
                'port' => null,
            ],
            $options
        );
    }

    public function backup(BackupContext $context, InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('  <comment>%s</comment>', $this->getExportCommand('dump.sql', true)));

        $fileystem = $context->getFilesystem();
        $tempFilename = $fileystem->tempFilename();

        $process = $this->processFactory->create($this->getExportCommand($tempFilename));
        $process->start();

        $progressBar = new ProgressBar($output);
        $progressBar->setFormat('  [%bar%] %elapsed:6s% %memory:6s%');
        $progressBar->start();

        while ($process->isRunning()) {
            // waiting for process to finish

            usleep(50);
            $progressBar->advance();
        }

        $fileystem->addFile($tempFilename, 'dump.sql');

        $progressBar->finish();

        unlink($tempFilename);

        $output->writeln('');
    }

    protected function getExportCommand($file, $hidePassword = false)
    {
        $username = $this->options['username'];
        $password = $this->options['password'];
        $database = $this->options['database'];
        $host = $this->options['host'];
        $port = $this->options['port'];

        return sprintf(
            'mysqldump -u%s%s%s%s %s > %s',
            $username,
            isset($password) ? (' -p' . ($hidePassword ? '***' : "'" . addcslashes($password, "'") . "'")) : '',
            isset($host) ? (' -h ' . $host) : '',
            isset($port) ? (' -P ' . $port) : '',
            $database,
            $file
        );
    }
}
