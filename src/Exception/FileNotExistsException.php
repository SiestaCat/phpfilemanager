<?php

namespace Siestacat\Phpfilemanager\Exception;

class FileNotExistsException extends \Exception
{
    public function __construct(string $hash)
    {
        parent::__construct(sprintf('File not exists with hash "%s"', $hash));
    }
}