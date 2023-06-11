<?php

namespace Siestacat\Phpfilemanager\File;

use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

class File
{
    public function __construct(private string $hash, private string $path)
    {}

    public function getHash():string
    {
        return $this->hash;
    }

    public function getPath():string
    {
        return $this->path;
    }
}