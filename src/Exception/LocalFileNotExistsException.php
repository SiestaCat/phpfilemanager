<?php

namespace Siestacat\Phpfilemanager\Exception;

class LocalFileNotExistsException extends \Exception
{
    public function __construct(string $local_path)
    {
        parent::__construct(sprintf('Local file path "%s" not exists', $local_path));
    }
}