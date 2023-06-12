<?php

namespace Siestacat\Phpfilemanager\Tests\File\Repository\Adapter;

use PHPUnit\Framework\TestCase;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemAdapter;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemException\HashLengthException;

class FileSystemAdapterTest extends TestCase
{
    public function testHashLengthError()
    {
        $this->expectException(HashLengthException::class);

        FileSystemAdapter::checkHashLength('abc', true);
    }
}