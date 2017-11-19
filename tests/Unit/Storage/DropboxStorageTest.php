<?php

namespace Nanbando\Tests\Unit\Storage;

use Nanbando\Storage\DropboxStorage;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Spatie\Dropbox\Client;

class DropboxStorageTest extends TestCase
{
    public function testUpload()
    {
        $filename = '20171117-233030';
        $localPath = __FILE__;

        $client = $this->prophesize(Client::class);
        $client->upload(
            '/' . ltrim(sprintf('/tmp/%s.tar.gz', $filename), '/'),
            Argument::type('resource'),
            'overwrite'
        )->shouldBeCalled();

        $dropboxStorage = new DropboxStorage('...', 'tmp');
        $dropboxStorage->setClient($client->reveal());

        $this->assertEquals($dropboxStorage, $dropboxStorage->upload($filename, $localPath));
    }

    public function testDownload()
    {
        $filename = '20171117-233030';
        $localPath = __FILE__ . '.test';

        $client = $this->prophesize(Client::class);
        $client->download('/' . ltrim(sprintf('/tmp/%s.tar.gz', $filename), '/'))
            ->shouldBeCalled()
            ->willReturn(fopen(__FILE__, 'r'));

        $dropboxStorage = new DropboxStorage('...', 'tmp');
        $dropboxStorage->setClient($client->reveal());

        $this->assertEquals($dropboxStorage, $dropboxStorage->download($filename, $localPath));

        unlink($localPath);
    }

    public function testExists()
    {
        $filename = '20171117-233030';

        $client = $this->prophesize(Client::class);
        $client->listFolder('/tmp')->willReturn(['entries' => [['path_lower' => '/' . $filename . '.tar.gz']]]);

        $dropboxStorage = new DropboxStorage('...', 'tmp');
        $dropboxStorage->setClient($client->reveal());

        $this->assertEquals(['20171117-233030'], $dropboxStorage->listFiles());
    }

    public function testList()
    {
        $filename = '20171117-233030';

        $client = $this->prophesize(Client::class);
        $client->listFolder('/tmp')->willReturn(['entries' => [['path_lower' => '/' . $filename . '.tar.gz']]]);

        $dropboxStorage = new DropboxStorage('...', 'tmp');
        $dropboxStorage->setClient($client->reveal());

        $this->assertEquals([$filename], $dropboxStorage->listFiles());
    }
}
