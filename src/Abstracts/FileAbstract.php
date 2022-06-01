<?php

namespace Incapption\FileSystem\Abstracts;

use Incapption\FileSystem\Exceptions\FileSystemException;
use Incapption\FileSystem\Interfaces\iFile;

abstract class FileAbstract implements iFile
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var string
     */
    protected $fullPath;

    /**
     * @var string
     */
    protected $folder;


    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var integer
     */
    protected $lastModified;

    /**
     * @var boolean
     */
    protected $isWritten = false;

    /**
     * @param  string|null  $fullPath
     */
    public abstract function __construct(?string $fullPath = null);

    /**
     * Writes a file to a destination
     *
     * @param  string  $destinationFullPath
     * @param  mixed  $content
     * @return iFile
     * @throws FileSystemException
     */
    public abstract function write(string $destinationFullPath, $content): iFile;

    /**
     * Moves a file to a different folder
     *
     * Example: $file->move('var/www/newFolder/');
     *
     * @param  string  $destinationFolder
     * @return iFile
     */
    public abstract function move(string $destinationFolder): iFile;

    /**
     * Renames a file
     *
     * Example: $file->rename('Foobar.jpg');
     *
     * @param  string  $newName
     * @return iFile
     */
    public abstract function rename(string $newName): iFile;

    /**
     * Deletes a file
     *
     * The instantiated variables are still accessible afterwards
     *
     * Example: $file->delete();
     *
     * $file->getName() => returns the name of the deleted file
     *
     * @return iFile
     */
    public abstract function delete(): iFile;

    /**
     * Copies a file to a full path
     *
     * Example: $file->copy('/var/my/new/path/filename.jpg');
     *
     * @param  string  $destinationFullPath
     * @return iFile
     */
    public abstract function copy(string $destinationFullPath): iFile;

    /**
     * Gets the content of the file as a string
     *
     * @return string
     */
    public abstract function getContent(): string;

    /**
     * Gets the name of the file.
     *
     * Example: $file->getName() => "testFile.jpg"
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the size of the file in bytes.
     *
     * Example: $file->getSize() => 1024
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Gets the full path of the file.
     *
     * Example: $file->getFullPath() => "/var/www/html/testFile.jpg"
     *
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Gets the folder of the file.
     *
     * "/var/www/html/testFile.jpg"
     *
     * Example: $file->getFolder() => "/var/www/html"
     *
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * Gets the mime type of the file.
     *
     * Example: $file->getMimeType() => "image/jpeg"
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Gets the extension of the file.
     *
     * Example: $file->getExtension() => ".jpg"
     *
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Gets the unix timestamp of the last modified date of the file.
     *
     * Example: $file->getLastModified() => 1654074213
     *
     * @return int
     */
    public function getLastModified(): int
    {
        return $this->lastModified;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'fullPath' => $this->getFullPath(),
            'mimeType' => $this->getMimeType(),
            'extension' => $this->getExtension(),
            'lastModified' => $this->getLastModified(),
        ];
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param  string  $fullPath
     * @return iFile
     */
    protected abstract function initialize(string $fullPath): iFile;

    /**
     * Sets the name for the file.
     *
     * Example: /var/www/html/testFile.jpg
     *
     * => $file->setName('testFile.jpg');
     *
     * @param  string  $name
     * @return iFile
     * @throws FileSystemException
     */
    protected function setName(string $name): iFile
    {
        if (empty($name))
        {
            throw new FileSystemException("name can not be empty");
        }

        // set the extension if file has one
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        if(!empty($extension))
        {
            $this->setExtension($extension);
        }
        else
        {
            $this->setExtension(null);
        }

        $this->name = $name;
        return $this;
    }

    /**
     * Sets the actual size for the file.
     *
     * Notice: The size is specified in bytes
     *
     * Example: file.txt has 1024 Byte
     *
     * => $file->setSize(1024);
     *
     * @param  int  $size
     * @return iFile
     */
    protected function setSize(int $size): iFile
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Sets the full path of the file.
     *
     * Example: /var/www/html/application/public/images/banner.jpg
     *
     * @param  string  $fullPath
     * @return iFile
     * @throws FileSystemException
     */
    protected function setFullPath(string $fullPath): iFile
    {
        if (empty($fullPath))
        {
            throw new FileSystemException("the full path can not be empty");
        }

        $this->fullPath = $fullPath;
        return $this;
    }

    /**
     * Sets the folder of the file.
     *
     * Example: /var/www/html/application/public/images
     *
     * @param  string  $folder
     * @return iFile
     * @throws FileSystemException
     */
    protected function setFolder(string $folder): iFile
    {
        if (empty($folder))
        {
            throw new FileSystemException("the folder can not be empty");
        }

        $this->folder = $folder;
        return $this;
    }

    /**
     * Sets the mimeType for the file.
     *
     * @param  string  $mimeType
     * @return iFile
     * @throws FileSystemException
     */
    protected function setMimeType(string $mimeType): iFile
    {
        if (empty($mimeType))
        {
            throw new FileSystemException("mimeType can not be empty");
        }

        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Sets the extension name for the file.
     *
     * @param  string  $extension
     * @throws FileSystemException
     */
    private function setExtension(string $extension): void
    {
        if (empty($extension))
        {
            throw new FileSystemException("extension name can not be empty");
        }

        if ($extension[0] !== ".")
        {
            $extension = '.'.$extension;
        }

        $this->extension = $extension;
    }

    /**
     * Sets the last modified date as unix timestamp for the file.
     *
     * @param  int  $lastModified
     * @return iFile
     */
    protected function setLastModified(int $lastModified): iFile
    {
        $this->lastModified = $lastModified;
        return $this;
    }
}