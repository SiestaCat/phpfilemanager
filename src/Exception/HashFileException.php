<?php

namespace Siestacat\Phpfilemanager\Exception;

class HashFileException extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('Unable to hash file "%s"', $path));
    }
}