<?php

namespace Incapption\FileSystem;

use Incapption\FileSystem\Exceptions\InvalidFileTypeException;
use Incapption\FileSystem\Interfaces\FileInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;

class File extends Filesystem implements FileInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @param  string  $filePath
     * @param  FilesystemAdapter  $adapter
     */
    public function __construct(string $filePath, FilesystemAdapter $adapter)
    {
        parent::__construct($adapter);

        $this->filePath = $filePath;
    }

    /**
     * @param  array  $mime_types
     * @return FileInterface
     * @throws InvalidFileTypeException|FilesystemException
     */
    public function allowMimeType(array $mime_types): FileInterface
    {
        if (!in_array($this->getMimeType(), $mime_types))
        {
            throw new InvalidFileTypeException('File::construct : File type is not allowed: '.$this->getMimeType());
        }

        return $this;
    }

    /**
     * @param  array  $mime_types
     * @return FileInterface
     * @throws InvalidFileTypeException|FilesystemException
     */
    public function disallowMimeType(array $mime_types): FileInterface
    {
        if (in_array($this->getMimeType(), $mime_types))
        {
            throw new InvalidFileTypeException('File::construct : File type is not allowed: '.$this->getMimeType());
        }

        return $this;
    }

    /**
     * @param  string  $dest
     * @param $contents
     * @return FileInterface
     * @throws FilesystemException
     */
    public function __write(string $dest, $contents): FileInterface
    {
        $this->write($dest, $contents);

        $this->filePath = $dest;

        return $this;
    }

    /**
     * @param  string  $dest
     * @return FileInterface
     * @throws FilesystemException
     */
    public function __move(string $dest): FileInterface
    {
        $this->move($this->filePath, $dest);

        $this->filePath = $dest;

        return $this;
    }

    /**
     * @param  string  $new_name
     * @return FileInterface
     * @throws FilesystemException
     */
    public function __rename(string $new_name): FileInterface
    {
        $newFilePath = dirname($this->filePath).DIRECTORY_SEPARATOR.$new_name;

        $this->move($this->filePath, $newFilePath);

        return $this;
    }

    /**
     * @param  string  $dest
     * @return FileInterface
     * @throws FilesystemException
     */
    public function __copy(string $dest): FileInterface
    {
        $this->copy($this->filePath, $dest);

        return $this;
    }

    /**
     * @return bool
     */
    public function __delete(): bool
    {
        try
        {
            $this->delete($this->filePath);
        }
        catch (FilesystemException $e)
        {
            return false;
        }

        return true;
    }

    /**
     * @param  string  $prefix
     * @return FileInterface
     * @throws FilesystemException
     */
    public function setRandomName(string $prefix = ''): FileInterface
    {
        $randomName = sha1(mt_rand()).'.'.pathinfo($this->getFileName(), PATHINFO_EXTENSION);
        $randomName = strlen($prefix) > 0 ? $prefix.'_'.$randomName : $randomName;

        $this->__rename($randomName);

        return $this;
    }

    /**
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return basename($this->filePath);
    }

    /**
     * @return int
     * @throws FilesystemException
     */
    public function getFileSize(): int
    {
        return $this->fileSize($this->filePath);
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->getFileName(), PATHINFO_EXTENSION);
    }

    /**
     * @return string
     * @throws FilesystemException
     */
    public function getMimeType(): string
    {
        return $this->mimeType($this->filePath);
    }

    /**
     * @return string
     */
    public function getDirectoryName(): string
    {
        return dirname($this->getFullPath()).DIRECTORY_SEPARATOR;
    }

    /**
     * @return array
     * @throws FilesystemException
     */
    public function toArray(): array
    {
        return array(
            'file_name'      => $this->getFileName(),
            'file_size'      => $this->getFileSize(),
            'file_extension' => $this->getFileExtension(),
            'file_mime_type' => $this->getMimeType(),
            'full_path'      => $this->getFullPath(),
            'directory_name' => $this->getDirectoryName()
        );
    }
}
