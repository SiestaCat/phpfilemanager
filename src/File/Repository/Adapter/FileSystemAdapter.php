<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter;

use Siestacat\Phpfilemanager\File\File;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemException\HashLengthException;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemException\MakeDirException;
use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

final class FileSystemAdapter implements AdapterInterface
{

    const HASH_DIR_DEEP = 2;
    const HASH_DIR_LENGTH = 2;
    const MIN_HASH_LENGTH = 8;

    public function __construct(private string $data_dir)
    {
        if(!is_dir($this->data_dir) && !mkdir($this->data_dir, 0777, true)) throw new MakeDirException($this->data_dir);

        $this->data_dir = realpath($this->data_dir) . DIRECTORY_SEPARATOR;
    }

    public function get(string $hash):File
    {
        $path = $this->getPath($hash);

        return new File($hash, $path);
    }

    public function add(string $hash, string $local_path):File
    {
        $path = $this->getPath($hash);

        $dir = dirname($path);

        if(!is_dir($dir) && !mkdir($dir, 0777, true)) throw new MakeDirException($dir);

        copy($local_path, $path);

        return new File($hash, $path);
    }

    public function exists(string $hash, ?string $path = null):bool
    {

        if(!self::checkHashLength($hash)) return false;

        $path = $path === null ? $this->getPath($hash) : $path;

        return is_file($path);
    }

    public function del(string $hash):bool
    {
        $path = $this->getPath($hash);

        $unlink = true;

        if($this->exists($hash, $path)) $unlink = unlink($path);

        $dir = dirname($path);

        for($a=1;$a<=self::HASH_DIR_DEEP;$a++)
        {

            if(!file_exists($dir) || (file_exists($dir) && !is_dir($dir))) break;

            $this->checkEmptyDir($dir);

            $dir = dirname($dir);
        }

        return $unlink;
    }

    public function getPath(string $hash):string
    {

        self::checkHashLength($hash, true);

        return $this->data_dir . $this->hashToRelPath($hash);
    }

    private function checkEmptyDir(string $dir):void
    {
        $files = scandir($dir);

        if(!is_array($files)) return;

        if(count($files) === 2) rmdir($dir);
    }

    private function hashToRelPath(string $hash):string
    {
        return join
        (
            DIRECTORY_SEPARATOR,
            array_merge
            (
                str_split
                (
                    substr($hash, 0, (self::HASH_DIR_DEEP * self::HASH_DIR_LENGTH)),
                    2
                ),
                [
                    $hash
                ]
            )
        );
    }

    public static function checkHashLength(string $hash, bool $throw = false):bool
    {
        $status = strlen($hash) >= self::MIN_HASH_LENGTH;

        if(!$status && $throw) throw new HashLengthException;

        return $status;
    }
}