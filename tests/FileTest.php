<?php

namespace Incapption\FileSystem\Tests;

use Incapption\FileSystem\File;
use Incapption\FileSystem\LocalFile;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        $this->adapter = new LocalFilesystemAdapter(
            dirname(__DIR__)
        );
    }

    public static function tearDownAfterClass(): void
    {
        $testFiles = glob('./tests/TestStorage/*.*');

        foreach ($testFiles as $testFile)
        {
            if (is_file($testFile) && basename($testFile) !== ".gitkeep")
            {
                unlink($testFile);
            }
        }

        $testFiles = glob('./tests/TestStorage/Subfolder/*.*');

        foreach ($testFiles as $testFile)
        {
            if (is_file($testFile) && basename($testFile) !== ".gitkeep")
            {
                unlink($testFile);
            }
        }
    }

    /** @test */
    public function write_a_file()
    {
        $file = new File($this->adapter, null);
        $file->__write('./tests/TestStorage/test1.jpg', file_get_contents('./tests/Storage/test.jpg'));

        $this->assertFileExists('./tests/TestStorage/test1.jpg');
    }

    /** @test */
    public function write_a_file_as_stream()
    {
        $file = new File($this->adapter, null);
        $file->__writeStream('./tests/TestStorage/5MB_streamed.bin', fopen('./tests/Storage/5MB.bin', 'r'));

        $this->assertFileExists('./tests/TestStorage/5MB_streamed.bin');
    }

    /** @test */
    public function instantiate_a_file()
    {
        $file = new File($this->adapter, './tests/TestStorage/5MB_streamed.bin');

        $this->assertEquals('5MB_streamed.bin', $file->toArray()['file_name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['file_mime_type']);
        $this->assertEquals('.bin', $file->toArray()['file_extension']);

        $this->assertNotEmpty($file->toArray()['full_path']);
        $this->assertNotEmpty($file->toArray()['file_size']);
        $this->assertNotEmpty($file->toArray()['file_last_modified']);
        $this->assertNotEmpty($file->toArray()['directory_name']);
    }

    /** @test */
    public function move_a_file()
    {
        $file = new File($this->adapter, './tests/TestStorage/5MB_streamed.bin');

        $file->__move('./tests/TestStorage/Subfolder/5MB_streamed.bin');

        $this->assertFalse(file_exists('tests/TestStorage/5MB_streamed.bin'));
        $this->assertTrue(file_exists('tests/TestStorage/Subfolder/5MB_streamed.bin'));
        $this->assertEquals(1, substr_count($file->toArray()['full_path'], 'tests/TestStorage/Subfolder'));
        $this->assertEquals(1, substr_count($file->toArray()['directory_name'], 'tests/TestStorage/Subfolder'));
    }

    /** @test */
    public function rename_a_file()
    {
        $file = new File($this->adapter, './tests/TestStorage/Subfolder/5MB_streamed.bin');

        $file->__rename('5MB_streamed_new_name.bin');

        $this->assertFalse(file_exists('tests/TestStorage/Subfolder/5MB_streamed.bin'));
        $this->assertTrue(file_exists('tests/TestStorage/Subfolder/5MB_streamed_new_name.bin'));

        $this->assertEquals(1, substr_count($file->toArray()['full_path'], 'tests/TestStorage/Subfolder/5MB_streamed_new_name.bin'));

        $this->assertEquals('5MB_streamed_new_name.bin', $file->toArray()['file_name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['file_mime_type']);
        $this->assertEquals('.bin', $file->toArray()['file_extension']);

    }

    /** @test */
    public function copy_a_file()
    {
        $file = new File($this->adapter, './tests/TestStorage/Subfolder/5MB_streamed_new_name.bin');

        $file->__copy('./tests/TestStorage/Subfolder/5MB_streamed_copied.bin');

        $this->assertTrue(file_exists('tests/TestStorage/Subfolder/5MB_streamed_copied.bin'));
        $this->assertTrue(file_exists('tests/TestStorage/Subfolder/5MB_streamed_new_name.bin'));

        $this->assertEquals(1, substr_count($file->toArray()['full_path'], 'tests/TestStorage/Subfolder/5MB_streamed_new_name.bin'));

        $this->assertEquals('5MB_streamed_new_name.bin', $file->toArray()['file_name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['file_mime_type']);
        $this->assertEquals('.bin', $file->toArray()['file_extension']);

        // instantiate copy
        $file = new File($this->adapter, './tests/TestStorage/Subfolder/5MB_streamed_copied.bin');

        $this->assertEquals(1, substr_count($file->toArray()['full_path'], 'tests/TestStorage/Subfolder/5MB_streamed_copied.bin'));

        $this->assertEquals('5MB_streamed_copied.bin', $file->toArray()['file_name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['file_mime_type']);
        $this->assertEquals('.bin', $file->toArray()['file_extension']);
    }

    /** @test */
    public function delete_a_local_file()
    {
        $file = new File($this->adapter, './tests/TestStorage/Subfolder/5MB_streamed_copied.bin');
        $file->__delete();

        $this>$this->expectException(UnableToReadFile::class);

        $this->assertEquals(1, substr_count($file->toArray()['full_path'], 'tests/TestStorage/Subfolder/5MB_streamed_copied.bin'));

        $this->assertEquals('5MB_streamed_copied.bin', $file->toArray()['file_name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['file_mime_type']);
        $this->assertEquals('.bin', $file->toArray()['file_extension']);
    }
}