<?php

namespace Siestacat\Phpfilemanager\Tests\File;
use PHPUnit\Framework\TestCase;
use Siestacat\Phpfilemanager\Exception\FileNotExistsException;
use Siestacat\Phpfilemanager\Exception\HashFileException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotExistsException;
use Siestacat\Phpfilemanager\Exception\LocalFileNotReadableException;
use Siestacat\Phpfilemanager\Exception\LocalPathNullException;
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

    public function testAddFileNotExists():void
    {
        $this->expectException(LocalFileNotExistsException::class);

        $this->getCommander()->add(self::genRandomHash() . '.zip');
    }

    public function testAddFileNotReadable():void
    {
        $unreadable_file = __DIR__ . '/../unreadable_file.txt';

        $fileperms = fileperms($unreadable_file);

        chmod($unreadable_file, 0000);

        try
        {
            $this->getCommander()->add($unreadable_file);
        }
        catch(LocalFileNotReadableException $e)
        {
            chmod($unreadable_file, $fileperms);
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);

        
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
        $this->expectException(FileNotExistsException::class);

        $this->getCommander()->del(self::genRandomHash());
    }

    public function testDel():void
    {

        $local_path = __FILE__;

        $commander = $this->getCommander();

        $hash = $commander->hash_file($local_path);

        $file = $commander->get($hash);

        $this->assertTrue($this->getCommander()->del($file->getHash()));
    }

    public function testHashFileException():void
    {
        $this->expectException(\ValueError::class);

        $this->expectExceptionMessage('hash_file');

        $this->getCommander()->hash_file(__FILE__, 'hash_algo_not_exists');
    }

    public function testAddHashExists():void
    {
        $local_path = __FILE__;

        $commander = $this->getCommander();

        $hash = $commander->hash_file($local_path);

        $commander->add($local_path);

        $file = $commander->add(null, $hash);

        $this->assertEquals($file->getHash(), $hash);
    }

    public function testAddHashNotExists():void
    {
        $this->expectException(LocalPathNullException::class);

        $this->getCommander()->add(null, self::genRandomHash());
    }
}