<?php

namespace Nanbando\Cleanup;

use Nanbando\Clock\ClockInterface;
use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
use Symfony\Component\Filesystem\Filesystem;

class Cleaner
{
    /**
     * @var OutputFormatter
     */
    private $output;

    /**
     * @var ClockInterface
     */
    private $clock;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var StrategyInterface|null
     */
    private $strategy;

    public function __construct(
        OutputFormatter $output,
        ClockInterface $clock,
        Filesystem $filesystem,
        ?StrategyInterface $strategy = null
    ) {
        $this->output = $output;
        $this->clock = $clock;
        $this->filesystem = $filesystem;
        $this->strategy = $strategy;
    }

    public function isEnabled()
    {
        return null !== $this->strategy;
    }

    public function clean(LocalStorage $storage): void
    {
        if (!$this->strategy) {
            return;
        }

        $archives = $storage->listFiles();
        $size = $storage->size();

        $this->output->headline('Cleanup started at %s', $this->clock->getDateTime());
        $this->output->list(
            [
                'count' => count($archives),
                'size' => $size,
            ]
        );

        $keep = $this->strategy->determineArchives($this->buildDependencies($archives, $storage));
        if (count($archives) === count($keep)) {
            $this->output->warning('No archives were removed');

            return;
        }

        foreach ($archives as $archiveInfo) {
            if (array_key_exists($archiveInfo->getName(), $keep)) {
                continue;
            }

            $section = $this->output->section();
            $section->warning('Removing archive %s', $archiveInfo->getName());

            $this->filesystem->remove($archiveInfo->getDatabasePath());
            $this->filesystem->remove($archiveInfo->getArchivePath());

            $section->clear();
            $section->checkmark('Removing archive %s', $archiveInfo->getName());
        }

        $this->output->info('Cleanup finished at %s', $this->clock->getDateTime());
        $this->output->list(
            [
                'count' => count($storage->listFiles()),
                'size' => $storage->size(),
            ]
        );
    }

    /**
     * @param ArchiveInfo[] $archivesInfos
     *
     * @return ArchiveInfo[]
     */
    private function buildDependencies(array $archivesInfos, LocalStorage $storage): array
    {
        $archives = [];
        foreach ($archivesInfos as $archive) {
            $database = $archive->openDatabase();
            if (!$archive->isFetched()) {
                continue;
            }

            $archives[$archive->getName()] = [$archive->getName() => $archive];
            if ($database->has('parent')) {
                $archives[$archive->getName()][$database->get('parent')] = $storage->get($database->get('parent'));
            }
        }

        return $archives;
    }
}
