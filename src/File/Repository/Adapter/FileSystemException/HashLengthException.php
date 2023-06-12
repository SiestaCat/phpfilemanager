<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemException;

use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemAdapter;

class HashLengthException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Hash length might be >=%d', FileSystemAdapter::MIN_HASH_LENGTH);
    }
}