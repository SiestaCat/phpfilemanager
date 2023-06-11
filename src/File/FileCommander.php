<?php

namespace Siestacat\Phpfilemanager\File;

use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

class FileCommander
{

    const DEFAULT_HASH_ALGO = 'sha512';

    public function __construct(private AdapterInterface $adapter, private string $hash_algo = self::DEFAULT_HASH_ALGO)
    {}

    public function get(string $hash):File
    {
        return $this->adapter->get($hash);
    }

    public function add(string $local_path, ?string $hash = null):File
    {
        $hash = $hash === null ? $this->hash_file($local_path) : $hash;

        return $this->adapter->add($hash, $local_path);
    }

    public function exists(string $hash):bool
    {
        return $this->adapter->exists($hash);
    }

    public function del(string $hash):bool
    {
        return $this->adapter->del($hash);
    }

    public function hash_file(string $local_path):string
    {
        return hash_file($this->hash_algo, $local_path);
    }
}