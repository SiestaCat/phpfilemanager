<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter;

use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

final class FileRepository implements AdapterInterface
{
    public function __construct(private string $abs_path)
    {
        
    }
}