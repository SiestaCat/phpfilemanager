<?php

namespace Siestacat\Phpfilemanager\File;

use Siestacat\Phpfilemanager\Exception\FileNotExistsException;
use Siestacat\Phpfilemanager\Exception\HashFileException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotExistsException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotReadableException;
use Siestacat\Phpfilemanager\Exception\LocalPathNullException;
use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

/**
 * Class to call any file adapter and do things
 * @package Siestacat\Phpfilemanager\File
 */
class FileCommander
{

    const DEFAULT_HASH_ALGO = 'sha512';

    public function __construct(private AdapterInterface $adapter, private string $hash_algo = self::DEFAULT_HASH_ALGO)
    {}

    /**
     * Get file object by hash
     * @param string $hash 
     * @return File 
     * @throws FileNotExistsException 
     */
    public function get(string $hash):File
    {
        if(!$this->exists($hash)) throw new FileNotExistsException($hash);

        return $this->adapter->get($hash);
    }

    public function list(int $page = 1, int $page_limit = AdapterInterface::DEFAULT_PAGE_LIMIT):array
    {
        return $this->adapter->list($page, $page_limit);
    }

    /**
     * Add file
     * @param string $local_path 
     * @param null|string $hash 
     * @return File 
     * @throws LocalFileNotExistsException 
     * @throws LocalFileNotReadableException 
     * @throws HashFileException 
     */
    public function add(?string $local_path, ?string $hash = null):File
    {

        if($hash !== null && $this->exists($hash))
        {
            return $this->get($hash);
        }

        if($local_path === null) throw new LocalPathNullException;

        if(!is_file($local_path)) throw new LocalFileNotExistsException($local_path);

        if(!is_readable($local_path)) throw new LocalFileNotReadableException($local_path);

        $hash = $hash === null ? $this->hash_file($local_path) : $hash;

        return $this->adapter->add($hash, $local_path);
    }

    /**
     * Check file exists
     * @param string $hash 
     * @return bool 
     */
    public function exists(string $hash):bool
    {
        return $this->adapter->exists($hash);
    }

    /**
     * Delete file
     * @param string $hash 
     * @return bool 
     * @throws FileNotExistsException 
     */
    public function del(string $hash):bool
    {
        if(!$this->exists($hash)) throw new FileNotExistsException($hash);

        return $this->adapter->del($hash);
    }

    /**
     * Hash local file
     * @param string $local_path 
     * @param null|string $hash_algo 
     * @return string 
     * @throws HashFileException 
     */
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