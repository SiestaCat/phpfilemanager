<?php

namespace Siestacat\Phpfilemanager\Tests\File;
use PHPUnit\Framework\TestCase;
use Siestacat\Phpfilemanager\File\File;
use Siestacat\Phpfilemanager\File\FileCommander;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemAdapter;

class FileCommanderTest extends TestCase
{

    private string $fs_data_dir = __DIR__ . '/../fs_data';

    private function getCommander():FileCommander
    {
        return new FileCommander(new FileSystemAdapter($this->fs_data_dir));
    }

    private static function genRandomHash():string
    {
        return hash(FileCommander::DEFAULT_HASH_ALGO, random_bytes(128));
    }

    public function testAdd():void
    {

        $local_path = __FILE__;

        $commander = $this->getCommander();

        $commander->add($local_path);

        $this->assertTrue($commander->exists($commander->hash_file($local_path)));
    }

    public function testGet():void
    {

        $local_path = __FILE__;

        $commander = $this->getCommander();

        $hash = $commander->hash_file($local_path);

        $file = $commander->get($hash);

        $this->addToAssertionCount(1);
    }

    public function testExists():void
    {

        $local_path = __FILE__;

        $commander = $this->getCommander();

        $hash = $commander->hash_file($local_path);

        $this->assertTrue($commander->exists($hash));
    }

    public function testNotExists():void
    {
        $this->assertFalse($this->getCommander()->exists(self::genRandomHash()));
    }

    public function testDelNonExistent():void
    {
        $this->assertFalse($this->getCommander()->del(self::genRandomHash()));
    }

    public function testDel():void
    {

        $local_path = __FILE__;

        $commander = $this->getCommander();

        $hash = $commander->hash_file($local_path);

        $file = $commander->get($hash);

        $this->assertTrue($this->getCommander()->del($file->getHash()));
    }
}