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

    private function abstractAddSingle(string $local_path):string
    {

        $commander = $this->getCommander();

        $local_hash = $commander->hash_file($local_path);

        //test add

        $file = $commander->add($local_path);

        $this->assertEquals($file->getHash(), $local_hash);

        $this->assertTrue($commander->exists($local_hash), "File: " . $local_path);

        //test get

        $file = $commander->get($local_hash);

        return $local_hash;
    }

    public function abstractDel(string $local_hash):void
    {
        $commander = $this->getCommander();

        $file = $commander->get($local_hash);

        $this->assertTrue($commander->del($file->getHash()));
    }

    public function testAddMultiple():void
    {
        $tests_files_dir_path = __DIR__ . '/../tests_files/';

        foreach(scandir($tests_files_dir_path) as $filename)
        {
            if(in_array($filename, ['.', '..'])) continue;

            $local_path = $tests_files_dir_path . $filename;

            $local_hash = $this->abstractAddSingle($local_path);
            $this->abstractDel($local_hash);
        }
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

    public function testNotExists():void
    {
        $this->assertFalse($this->getCommander()->exists(self::genRandomHash()));
    }

    public function testDelNonExistent():void
    {
        $this->expectException(FileNotExistsException::class);

        $this->getCommander()->del(self::genRandomHash());
    }

    public function testHashFileException():void
    {
        $this->expectException(\ValueError::class);

        $this->expectExceptionMessage('hash_file');

        $this->getCommander()->hash_file(__FILE__, self::genRandomHash());
    }

    public function testAddHashNotExists():void
    {
        $this->expectException(LocalPathNullException::class);

        $this->getCommander()->add(null, self::genRandomHash());
    }
}