<?php

namespace Siestacat\Phpfilemanager\File;

use Siestacat\Phpfilemanager\Exception\FileNotExistsException;
use Siestacat\Phpfilemanager\Exception\HashFileException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotExistsException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotReadableException;
use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

class FileCommander
{

    const DEFAULT_HASH_ALGO = 'sha512';

    public function __construct(private AdapterInterface $adapter, private string $hash_algo = self::DEFAULT_HASH_ALGO)
    {}

    public function get(string $hash):File
    {
        if(!$this->exists($hash)) throw new FileNotExistsException($hash);

        return $this->adapter->get($hash);
    }

    public function add(string $local_path, ?string $hash = null):File
    {

        if(!is_file($local_path)) throw new LocalFileNotExistsException($local_path);

        if(!is_readable($local_path)) throw new LocalFileNotReadableException($local_path);

        $hash = $hash === null ? $this->hash_file($local_path) : $hash;

        return $this->adapter->add($hash, $local_path);
    }

    public function exists(string $hash):bool
    {
        return $this->adapter->exists($hash);
    }

    public function del(string $hash):bool
    {
        if(!$this->exists($hash)) throw new FileNotExistsException($hash);

        return $this->adapter->del($hash);
    }

    public function hash_file(string $local_path, ?string $hash_algo = null):string
    {
        $hash = hash_file
        (
            $hash_algo === null ? $this->hash_algo : $hash_algo,
            $local_path
        );

        if($hash === false) throw new HashFileException($local_path);

        return $hash;
    }
}