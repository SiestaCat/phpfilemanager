<?php

namespace Siestacat\Phpfilemanager\File\Repository;

use Siestacat\Phpfilemanager\File\File;

interface AdapterInterface
{

    const DEFAULT_PAGE_LIMIT = 30;

    /**
     * Get file
     * @param string $hash 
     * @return File 
     */
    public function get(string $hash):File;

    /**
     * Listing all files
     * @param int $page 
     * @return File[] 
     */
    public function list(int $page, int $page_limit = self::DEFAULT_PAGE_LIMIT):array;

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