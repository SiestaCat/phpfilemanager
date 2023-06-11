<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter;

use Siestacat\Phpfilemanager\File\File;
use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

final class FileSystemAdapter implements AdapterInterface
{

    const HASH_DIR_DEEP = 2;
    const HASH_DIR_LENGTH = 2;

    private string $abs_path;

    public function __construct(string $abs_path)
    {
        if(!in_array(substr($abs_path, -1), ['/', '\\']))
        {
            $abs_path .= DIRECTORY_SEPARATOR;
        }

        $this->abs_path = $abs_path;
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

        if(!is_dir($dir)) mkdir($dir, 0777, true);

        copy($local_path, $path);

        return new File($hash, $path);
    }

    public function exists(string $hash):bool
    {
        $path = $this->getPath($hash);

        return is_file($path);
    }

    public function del(string $hash):bool
    {
        $path = $this->getPath($hash);

        $unlink = unlink($path);

        $dir = dirname($path);

        for($a=1;$a<=self::HASH_DIR_DEEP;$a++)
        {

            $this->checkEmptyDir($dir);

            $dir = dirname($dir);
        }

        return $unlink;
    }

    public function getPath(string $hash):string
    {
        return $this->abs_path . $this->hashToRelPath($hash);
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
                    substr($hash, 4)
                ]
            )
        );
    }
}