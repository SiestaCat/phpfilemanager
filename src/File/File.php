<?php

namespace Siestacat\Phpfilemanager\File;

use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

/**
 * File object
 * @package Siestacat\Phpfilemanager\File
 */
class File
{
    public function __construct(private string $hash, private string $path)
    {}

    /**
     * Get file hash
     * @return string 
     */
    public function getHash():string
    {
        return $this->hash;
    }

    /**
     * Get local path
     * @return string 
     */
    public function getPath():string
    {
        return $this->path;
    }
}