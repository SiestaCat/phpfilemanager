<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemException;

class MakeDirException extends \Exception
{
    public function __construct(string $dir)
    {
        parent::__construct(sprintf('Unable to make dir "%s"', $dir));
    }
}