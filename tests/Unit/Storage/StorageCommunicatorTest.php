<?php

namespace Nanbando\Tests\Unit\Storage;

use Nanbando\Storage\DirectoryStorage;
use Nanbando\Storage\StorageCommunicator;
use Nanbando\Storage\StorageInterface;
use Nanbando\Storage\StorageRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StorageCommunicatorTest extends TestCase
{
    public function testPush()
    {
        $storageRegistry = $this->prophesize(StorageRegistry::class);
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $input->getArgument('remote')->willReturn('test');

        $directoryStorage = $this->prophesize(DirectoryStorage::class);
        $storage = $this->prophesize(StorageInterface::class);
        $storageRegistry->get('test')->willReturn($storage->reveal());

        $directoryStorage->listFiles()->willReturn(['20171119-075545', '20171119-085530']);

        $storage->exists('20171119-085530')->willReturn(true);
        $storage->exists('20171119-075545')->willReturn(false);
        $storage->upload('20171119-085530', Argument::any())->shouldNotBeCalled();
        $storage->upload('20171119-075545', '/tmp/20171119-075545.tar.gz')->shouldBeCalled();

        $communicator = new StorageCommunicator(
            $storageRegistry->reveal(),
            function ($directory) use ($directoryStorage) {
                $this->assertEquals($directory, '/tmp');

                return $directoryStorage->reveal();
            }
        );

        $communicator->push('/tmp', $input->reveal(), $output->reveal());
    }

    public function testFetch()
    {
        $storageRegistry = $this->prophesize(StorageRegistry::class);
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $input->getArgument('remote')->willReturn('test');
        $input->getOption('file')->willReturn('20171119-085530');

        $directoryStorage = $this->prophesize(DirectoryStorage::class);
        $storage = $this->prophesize(StorageInterface::class);
        $storageRegistry->get('test')->willReturn($storage->reveal());

        $directoryStorage->exists('20171119-085530')->willReturn(false);

        $storage->download('20171119-085530', '/tmp/20171119-085530.tar.gz')->shouldBeCalled();

        $communicator = new StorageCommunicator(
            $storageRegistry->reveal(),
            function ($directory) use ($directoryStorage) {
                $this->assertEquals($directory, '/tmp');

                return $directoryStorage->reveal();
            }
        );

        $communicator->fetch('/tmp', $input->reveal(), $output->reveal());
    }

    public function testFetchAlreadyExists()
    {
        $storageRegistry = $this->prophesize(StorageRegistry::class);
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $input->getArgument('remote')->willReturn('test');
        $input->getOption('file')->willReturn('20171119-085530');

        $directoryStorage = $this->prophesize(DirectoryStorage::class);
        $storage = $this->prophesize(StorageInterface::class);
        $storageRegistry->get('test')->willReturn($storage->reveal());

        $directoryStorage->exists('20171119-085530')->willReturn(true);

        $storage->download('20171119-085530', '/tmp/20171119-085530.tar.gz')->shouldNotBeCalled();

        $communicator = new StorageCommunicator(
            $storageRegistry->reveal(),
            function ($directory) use ($directoryStorage) {
                $this->assertEquals($directory, '/tmp');

                return $directoryStorage->reveal();
            }
        );

        $communicator->fetch('/tmp', $input->reveal(), $output->reveal());
    }

    public function testFetchOnlyOneFile()
    {
        $storageRegistry = $this->prophesize(StorageRegistry::class);
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $input->getArgument('remote')->willReturn('test');
        $input->getOption('file')->willReturn(null);

        $directoryStorage = $this->prophesize(DirectoryStorage::class);
        $storage = $this->prophesize(StorageInterface::class);
        $storageRegistry->get('test')->willReturn($storage->reveal());

        $directoryStorage->exists('20171119-085530')->willReturn(false);

        $storage->listFiles()->willReturn(['20171119-085530']);
        $storage->download('20171119-085530', '/tmp/20171119-085530.tar.gz')->shouldBeCalled();

        $communicator = new StorageCommunicator(
            $storageRegistry->reveal(),
            function ($directory) use ($directoryStorage) {
                $this->assertEquals($directory, '/tmp');

                return $directoryStorage->reveal();
            }
        );

        $communicator->fetch('/tmp', $input->reveal(), $output->reveal());
    }

    public function testFetchOnlyOneFileButExists()
    {
        $storageRegistry = $this->prophesize(StorageRegistry::class);
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);

        $input->getArgument('remote')->willReturn('test');
        $input->getOption('file')->willReturn(null);

        $directoryStorage = $this->prophesize(DirectoryStorage::class);
        $storage = $this->prophesize(StorageInterface::class);
        $storageRegistry->get('test')->willReturn($storage->reveal());

        $directoryStorage->exists('20171119-085530')->willReturn(true);

        $storage->listFiles()->willReturn(['20171119-085530']);
        $storage->download('20171119-085530', '/tmp/20171119-085530.tar.gz')->shouldNotBeCalled();

        $communicator = new StorageCommunicator(
            $storageRegistry->reveal(),
            function ($directory) use ($directoryStorage) {
                $this->assertEquals($directory, '/tmp');

                return $directoryStorage->reveal();
            }
        );

        $communicator->fetch('/tmp', $input->reveal(), $output->reveal());
    }
}
