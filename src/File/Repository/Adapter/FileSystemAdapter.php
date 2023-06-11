<?php

namespace Siestacat\Phpfilemanager\File\Repository\Adapter;

use Siestacat\Phpfilemanager\File\File;
use Siestacat\Phpfilemanager\File\Repository\AdapterInterface;

final class FileSystemAdapter implements AdapterInterface
{

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

    public function del(string $hash):bool
    {
        $path = $this->getPath($hash);

        return unlink($path);
    }

    public function getPath(string $hash):string
    {
        return $this->abs_path . $this->hashToRelPath($hash);
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
                    substr($hash, 0, 4),
                    2
                ),
                [
                    substr($hash, 4)
                ]
            )
        );
    }
}