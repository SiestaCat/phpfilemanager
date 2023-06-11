<?php

namespace Siestacat\Phpfilemanager\Exception;

/**
 * Local file is no readable. Usually at try to put file to fileadapter
 * @package Siestacat\Phpfilemanager\Exception
 */
class LocalFileNotReadableException extends \Exception
{
    public function __construct(string $local_path)
    {
        parent::__construct(sprintf('Local file path "%s" not readable', $local_path));
    }
}