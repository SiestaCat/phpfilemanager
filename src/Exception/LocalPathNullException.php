<?php

namespace Siestacat\Phpfilemanager\Exception;

/**
 * Local file arg is null
 * @package Siestacat\Phpfilemanager\Exception
 */
class LocalPathNullException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Local file path is null');
    }
}