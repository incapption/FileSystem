<?php

namespace Incapption\FileSystem\Tests;

use Incapption\FileSystem\LocalFile;
use PHPUnit\Framework\TestCase;

class LocalFileTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function tearDownAfterClass(): void
    {
        $testFiles = glob('./tests/TestStorage/*.*');

        foreach ($testFiles as $testFile) {
            if (is_file($testFile) && basename($testFile) !== ".gitkeep") {
                unlink($testFile);
            }
        }

        $testFiles = glob('./tests/TestStorage/Subfolder/*.*');

        foreach ($testFiles as $testFile) {
            if (is_file($testFile) && basename($testFile) !== ".gitkeep") {
                unlink($testFile);
            }
        }
    }

    /** @test */
    public function write_a_local_file()
    {
        $file = new LocalFile();
        $file->write('./tests/TestStorage/test1.jpg', file_get_contents('./tests/Storage/test.jpg'));

        $this->assertFileExists('./tests/TestStorage/test1.jpg');
    }

    /** @test */
    public function instantiate_a_local_file()
    {
        $file = new LocalFile('./tests/TestStorage/test1.jpg');

        $this->assertEquals('test1.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function move_a_local_file()
    {
        $file = new LocalFile('./tests/TestStorage/test1.jpg');
        $file->move('./tests/TestStorage/Subfolder');

        $this->assertFalse(file_exists('./tests/TestStorage/test1.jpg'));

        $this->assertEquals('test1.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function rename_a_local_file()
    {
        $file = new LocalFile('./tests/TestStorage/Subfolder/test1.jpg');
        $file->rename('test3.jpg');

        $this->assertEquals('test3.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function rename_a_local_file_in_the_same_folder()
    {
        $file = new LocalFile('./tests/TestStorage/Subfolder/test3.jpg');

        $file->move('./tests');

        $this->assertTrue(file_exists('./tests/test3.jpg'));

        $file->rename('test4.jpg');

        $this->assertTrue(file_exists('./tests/test4.jpg'));

        $file->move('./tests/TestStorage/Subfolder');

        $this->assertTrue(file_exists('./tests/TestStorage/Subfolder/test4.jpg'));

        $this->assertEquals('test4.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function copy_a_local_file()
    {
        $file = new LocalFile('./tests/TestStorage/Subfolder/test4.jpg');
        $file->copy('./tests/TestStorage/test_copy.jpg');

        $this->assertEquals('test4.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function delete_a_local_file()
    {
        $file = new LocalFile('./tests/TestStorage/test_copy.jpg');
        $file->delete();

        $this->assertEquals('test_copy.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }
}