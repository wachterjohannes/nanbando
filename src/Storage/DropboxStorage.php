<?php

namespace Nanbando\Storage;

use Spatie\Dropbox\Client;

class DropboxStorage implements StorageInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $base;

    public function __construct(string $authorizationToken, ?string $base = null)
    {
        $this->client = new Client($authorizationToken);

        $this->base = $base;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function upload(string $filename, string $localPath): StorageInterface
    {
        $handler = fopen($localPath, 'rb');
        $this->client->upload('/' . ltrim(sprintf('%s/%s.tar.gz', $this->base, $filename), '/'), $handler, 'overwrite');

        return $this;
    }

    public function download(string $filename, string $localPath): StorageInterface
    {
        $handle = $this->client->download('/' . ltrim(sprintf('%s/%s.tar.gz', $this->base, $filename), '/'));

        file_put_contents($localPath, $handle);

        return $this;
    }

    public function listFiles(): array
    {
        $result = [];

        $metadata = $this->client->listFolder('/' . $this->base);
        foreach ($metadata['entries'] as $file) {
            $result[] = basename($file['path_lower'], '.tar.gz');
        }

        return $result;
    }

    public function exists(string $filename)
    {
        return in_array($filename, $this->listFiles());
    }
}
