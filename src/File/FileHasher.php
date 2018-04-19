<?php

namespace Nanbando\File;

class FileHasher
{
    public function hash(string $filePath): string
    {
        return hash_file('sha224', $filePath);
    }
}
