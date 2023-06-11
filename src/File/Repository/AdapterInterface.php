<?php

namespace Siestacat\Phpfilemanager\File\Repository;

use Siestacat\Phpfilemanager\File\File;

interface AdapterInterface
{
    /**
     * Get file
     * @param string $hash 
     * @return File 
     */
    public function get(string $hash):File;

    /**
     * Add file
     * @param string $hash 
     * @param string $local_path 
     * @return File 
     */
    public function add(string $hash, string $local_path):File;

    /**
     * File exists
     * @param string $hash 
     * @return bool 
     */
    public function exists(string $hash):bool;

    /**
     * Delete file
     * @param string $hash 
     * @return bool 
     */
    public function del(string $hash):bool;
}