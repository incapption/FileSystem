<?php

namespace Incapption\FileSystem\Tests;

use Dotenv\Dotenv;
use Incapption\FileSystem\Exceptions\FileDoesNotExist;
use Incapption\FileSystem\S3FileOld;
use PHPUnit\Framework\TestCase;

class S3FileTest extends TestCase
{
    private $options;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2), '.env.testing');
        $dotenv->load();

        $dotenv->required('S3_TEST_BUCKET');
        $dotenv->required('S3_TEST_ENDPOINT');
        $dotenv->required('S3_TEST_REGION');
        $dotenv->required('S3_TEST_ACL');
        $dotenv->required('S3_TEST_API_KEY');
        $dotenv->required('S3_TEST_API_SECRET');

        $this->options = [
            'bucket'    => $_ENV['S3_TEST_BUCKET'],
            'endpoint'  => $_ENV['S3_TEST_ENDPOINT'],
            'region'    => $_ENV['S3_TEST_REGION'],
            'acl'       => $_ENV['S3_TEST_ACL'],
            'apiKey'    => $_ENV['S3_TEST_API_KEY'],
            'apiSecret' => $_ENV['S3_TEST_API_SECRET']
        ];

        parent::__construct();
    }

    public static function tearDownAfterClass(): void {}


    /** @test */
    public function write_a_s3_file()
    {
        $s3file = new S3FileOld($this->options);
        $s3file->write('tests/test.jpg', './tests/Storage/test.jpg');

        $file = new S3FileOld($this->options, 'tests/test.jpg');

        $this->assertEquals('test.jpg', $file->toArray()['name']);
        $this->assertEquals('image/jpeg', $file->toArray()['mimeType']);
        $this->assertEquals('.jpg', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function write_a_s3_file_as_multipart()
    {
        $s3file = new S3FileOld($this->options);
        $s3file->writeMultiPart('tests/5MB.bin', './tests/Storage/5MB.bin', function ($totalSize, $uploaded)
        {
            $this->assertNotEmpty($totalSize);
        });

        $file = new S3FileOld($this->options, 'tests/5MB.bin');

        $this->assertEquals('5MB.bin', $file->toArray()['name']);
        $this->assertEquals('application/octet-stream', $file->toArray()['mimeType']);
        $this->assertEquals('.bin', $file->toArray()['extension']);

        $this->assertNotEmpty($file->toArray()['fullPath']);
        $this->assertNotEmpty($file->toArray()['size']);
        $this->assertNotEmpty($file->toArray()['lastModified']);
    }

    /** @test */
    public function instantiate_a_s3_file()
    {
        $s3File = new S3FileOld($this->options, 'tests/test.jpg');

        $this->assertEquals('test.jpg', $s3File->toArray()['name']);
        $this->assertEquals('image/jpeg', $s3File->toArray()['mimeType']);
        $this->assertEquals('.jpg', $s3File->toArray()['extension']);
        $this->assertEquals('tests', $s3File->toArray()['folder']);

        $this->assertNotEmpty($s3File->toArray()['fullPath']);
        $this->assertNotEmpty($s3File->toArray()['size']);
        $this->assertNotEmpty($s3File->toArray()['lastModified']);
    }

    /** @test */
    public function move_a_s3_file()
    {
        $s3File = new S3FileOld($this->options, 'tests/test.jpg');
        $s3File->moveTo('tests_moved');

        $this->assertEquals('test.jpg', $s3File->toArray()['name']);
        $this->assertEquals('image/jpeg', $s3File->toArray()['mimeType']);
        $this->assertEquals('.jpg', $s3File->toArray()['extension']);
        $this->assertEquals('tests_moved', $s3File->toArray()['folder']);

        $this->assertNotEmpty($s3File->toArray()['fullPath']);
        $this->assertNotEmpty($s3File->toArray()['size']);
        $this->assertNotEmpty($s3File->toArray()['lastModified']);
    }

    /** @test */
    public function rename_a_s3_file()
    {
        $s3File = new S3FileOld($this->options, 'tests_moved/test.jpg');
        $s3File->rename('test_rename.jpg');

        $this->assertEquals('test_rename.jpg', $s3File->toArray()['name']);
        $this->assertEquals('image/jpeg', $s3File->toArray()['mimeType']);
        $this->assertEquals('.jpg', $s3File->toArray()['extension']);
        $this->assertEquals('tests_moved', $s3File->toArray()['folder']);

        $this->assertNotEmpty($s3File->toArray()['fullPath']);
        $this->assertNotEmpty($s3File->toArray()['size']);
        $this->assertNotEmpty($s3File->toArray()['lastModified']);
    }

    /** @test */
    public function copy_a_s3_file()
    {
        $s3File = new S3FileOld($this->options, 'tests_moved/test_rename.jpg');
        $s3File->copyTo('tests_copied/test_copied.jpg');

        $this->assertEquals('test_copied.jpg', $s3File->toArray()['name']);
        $this->assertEquals('image/jpeg', $s3File->toArray()['mimeType']);
        $this->assertEquals('.jpg', $s3File->toArray()['extension']);
        $this->assertEquals('tests_copied', $s3File->toArray()['folder']);

        $this->assertNotEmpty($s3File->toArray()['fullPath']);
        $this->assertNotEmpty($s3File->toArray()['size']);
        $this->assertNotEmpty($s3File->toArray()['lastModified']);
    }

    /** @test */
    public function delete_a_s3_file()
    {
        $s3File = new S3FileOld($this->options, 'tests_copied/test_copied.jpg');
        $s3File->delete();

        $this->expectException(FileDoesNotExist::class);
        (new S3FileOld($this->options, 'tests_copied/test_copied.jpg'));
    }
}