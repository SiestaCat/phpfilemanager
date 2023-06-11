<?php

namespace Siestacat\Phpfilemanager\Exception;

/**
 * Local file not exists. Usually at try to put file to fileadapter
 * @package Siestacat\Phpfilemanager\Exception
 */
class LocalFileNotExistsException extends \Exception
{
    public function __construct(string $local_path)
    {
        parent::__construct(sprintf('Local file path "%s" not exists', $local_path));
    }
}