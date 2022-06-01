<?php

namespace Incapption\FileSystem\Tests;

use Incapption\FileSystem\Exceptions\DirectoryDoesNotExist;
use Incapption\FileSystem\Exceptions\FileAlreadyWritten;
use Incapption\FileSystem\Exceptions\FileDoesNotExist;
use Incapption\FileSystem\Exceptions\FileNotWritten;
use Incapption\FileSystem\LocalFile;
use PHPUnit\Framework\TestCase;

class LocalFileExceptionTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function local_file_does_not_exist()
    {
        $this->expectException(FileDoesNotExist::class);
        new LocalFile('/tests/some_folder/'.sha1(42).'.jpg');
    }

    /** @test */
    public function delete_not_instantiated_file()
    {
        $this->expectException(FileNotWritten::class);
        $file = new LocalFile();
        $file->delete();
    }

    /** @test */
    public function move_not_instantiated_file()
    {
        $this->expectException(FileNotWritten::class);
        $file = new LocalFile();
        $file->move('/test/TestStorage/foo');
    }

    /** @test */
    public function rename_not_instantiated_file()
    {
        $this->expectException(FileNotWritten::class);
        $file = new LocalFile();
        $file->rename('/test/TestStorage/foo');
    }

    /** @test */
    public function copy_not_instantiated_file()
    {
        $this->expectException(FileNotWritten::class);
        $file = new LocalFile();
        $file->copy('/test/TestStorage/foo');
    }

    /** @test */
    public function write_on_an_already_instantiated_file()
    {
        $this->expectException(FileAlreadyWritten::class);
        $file = new LocalFile('./tests/Storage/test.jpg');
        $file->write('./tests/TestStorage/test1.jpg', 'foobar');
    }

    /** @test */
    public function write_directory_does_not_exist()
    {
        $this->expectException(DirectoryDoesNotExist::class);
        $file = new LocalFile();
        $file->write('./tests/'.sha1(42).'/test1.jpg', 'foobar');
    }

    /** @test */
    public function move_directory_does_not_exist()
    {
        $this->expectException(DirectoryDoesNotExist::class);
        $file = new LocalFile('./tests/Storage/test.jpg');
        $file->move('./tests/'.sha1(42).'/test1.jpg');
    }

    /** @test */
    public function move_file_exists()
    {
        $this->expectException(DirectoryDoesNotExist::class);
        $file = new LocalFile('./tests/Storage/test.jpg');
        $file->move('./tests/Storage/test.jpg');
    }
}