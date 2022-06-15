<?php

namespace Incapption\FileSystem;

use Incapption\FileSystem\Interfaces\FileInterface;
use League\Flysystem\CorruptedPathDetected;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\PathTraversalDetected;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToWriteFile;

class File extends Filesystem implements FileInterface
{
    /**
     * @var string|null
     */
    public $filePath;

    /**
     * @param  FilesystemAdapter  $adapter
     * @param  string|null  $filePath
     * @throws CorruptedPathDetected|PathTraversalDetected
     */
    public function __construct(FilesystemAdapter $adapter, ?string $filePath = null)
    {
        parent::__construct($adapter);

        if ($filePath !== null)
        {
            $this->filePath = $filePath;
        }
    }

    /**
     * @return void
     * @throws UnableToReadFile
     */
    protected function checkObject(): void
    {
        if ($this->filePath === null)
            throw new UnableToReadFile('file path not set');
    }

    /**
     * @param  string  $dest
     * @param  string  $contents
     * @return FileInterface
     * @throws FilesystemException|UnableToWriteFile
     */
    public function __write(string $dest, $contents): FileInterface
    {
        $this->write($dest, $contents);

        $this->filePath = $dest;

        return $this;
    }

    /**
     * @param  string  $dest
     * @param  resource  $contents
     * @return FileInterface
     * @throws FilesystemException|UnableToWriteFile
     */
    public function __writeStream(string $dest, $contents): FileInterface
    {
        $this->writeStream($dest, $contents);

        $this->filePath = $dest;

        return $this;
    }

    /**
     * @param  string  $dest
     * @return FileInterface
     * @throws FilesystemException|UnableToMoveFile|UnableToReadFile
     */
    public function __move(string $dest): FileInterface
    {
        $this->checkObject();

        $this->move($this->filePath, $dest);

        $this->filePath = $dest;

        return $this;
    }

    /**
     * @param  string  $new_name
     * @return FileInterface
     * @throws FilesystemException|UnableToReadFile
     */
    public function __rename(string $new_name): FileInterface
    {
        $this->checkObject();

        $newFilePath = $this->getDirectoryName().DIRECTORY_SEPARATOR.$new_name;

        $this->__move($newFilePath);

        return $this;
    }

    /**
     * @param  string  $dest
     * @return FileInterface
     * @throws FilesystemException|UnableToCopyFile|UnableToReadFile
     */
    public function __copy(string $dest): FileInterface
    {
        $this->checkObject();

        $this->copy($this->filePath, $dest);

        return $this;
    }

    /**
     * @return bool
     * @throws FilesystemException|UnableToDeleteFile|UnableToReadFile
     */
    public function __delete(): bool
    {
        $this->checkObject();

        $this->delete($this->filePath);
        $this->filePath = null;

        return true;
    }

    /**
     * @return string
     * @throws FilesystemException|UnableToReadFile
     */
    public function getContent(): string
    {
        $this->checkObject();
        return $this->read($this->filePath);
    }

    /**
     * @return string
     * @throws UnableToReadFile
     */
    public function getFullPath(): string
    {
        $this->checkObject();
        return $this->filePath;
    }

    /**
     * @return string
     * @throws UnableToReadFile
     */
    public function getName(): string
    {
        $this->checkObject();
        return basename($this->filePath);
    }

    /**
     * @return int
     * @throws FilesystemException|UnableToRetrieveMetadata|UnableToReadFile
     */
    public function getSize(): int
    {
        $this->checkObject();
        return $this->fileSize($this->filePath);
    }

    /**
     * @return string
     * @throws UnableToReadFile
     */
    public function getExtension(): string
    {
        $this->checkObject();
        return '.'.pathinfo($this->getName(), PATHINFO_EXTENSION);
    }

    /**
     * @return string
     * @throws FilesystemException|UnableToRetrieveMetadata|UnableToReadFile
     */
    public function getMimeType(): string
    {
        $this->checkObject();
        return $this->mimeType($this->filePath);
    }

    /**
     * @return int
     * @throws FilesystemException|UnableToRetrieveMetadata|UnableToReadFile
     */
    public function getLastModified(): int
    {
        $this->checkObject();
        return $this->lastModified($this->filePath);
    }

    /**
     * @return string
     * @throws UnableToReadFile
     */
    public function getDirectoryName(): string
    {
        $this->checkObject();
        return dirname($this->getFullPath());
    }

    /**
     * @return array
     * @throws FilesystemException|UnableToReadFile
     */
    public function toArray(): array
    {
        $this->checkObject();

        return array(
            'file_name'          => $this->getName(),
            'file_size'          => $this->getSize(),
            'file_extension'     => $this->getExtension(),
            'file_mime_type'     => $this->getMimeType(),
            'file_last_modified' => $this->getLastModified(),
            'full_path'          => $this->getFullPath(),
            'directory_name'     => $this->getDirectoryName()
        );
    }

    /**
     * @return string
     * @throws FilesystemException|UnableToReadFile
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
