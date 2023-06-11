<?php

namespace Siestacat\Phpfilemanager\File\Repository;

use Siestacat\Phpfilemanager\File\File;

interface AdapterInterface
{
    public function get(string $hash):File;

    public function add(string $hash, string $local_path):File;

    public function del(string $hash):bool;
}