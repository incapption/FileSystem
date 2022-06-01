<?php

namespace Incapption\FileSystem;

use Incapption\FileSystem\Abstracts\FileAbstract;
use Incapption\FileSystem\Exceptions\DirectoryDoesNotExist;
use Incapption\FileSystem\Exceptions\DirectoryNotWritable;
use Incapption\FileSystem\Exceptions\FileAlreadyWritten;
use Incapption\FileSystem\Exceptions\FileCanNotBeDeleted;
use Incapption\FileSystem\Exceptions\FileCanNotBeMoved;
use Incapption\FileSystem\Exceptions\FileCanNotBeRenamed;
use Incapption\FileSystem\Exceptions\FileCanNotBeWritten;
use Incapption\FileSystem\Exceptions\FileContentNotReadable;
use Incapption\FileSystem\Exceptions\FileDoesNotExist;
use Incapption\FileSystem\Exceptions\FileNotWritable;
use Incapption\FileSystem\Exceptions\FileNotWritten;
use Incapption\FileSystem\Exceptions\FileSystemException;
use Incapption\FileSystem\Interfaces\iFile;

class LocalFile extends FileAbstract
{
    /**
     * @param  string|null  $fullPath
     * @throws Exceptions\FileSystemException
     */
    public function __construct(?string $fullPath = null)
    {
        if (is_null($fullPath)) {
            $this->isWritten = false;
        } else {
            $this->initialize($fullPath);
        }
    }


    /**
     * @param  string  $fullPath
     * @return iFile
     * @throws FileDoesNotExist
     * @throws FileSystemException
     */
    protected function initialize(string $fullPath): iFile
    {
        if (!file_exists($fullPath)) {
            throw new FileDoesNotExist($fullPath);
        }

        $this->setFullPath(realpath($fullPath))
            ->setName(basename($fullPath))
            ->setMimeType(mime_content_type($fullPath))
            ->setSize(filesize($fullPath))
            ->setFolder(dirname($fullPath))
            ->setLastModified(filemtime($fullPath));

        $this->isWritten = true;

        return $this;
    }


    /**
     * @param  bool  $anticipation
     * @return void
     * @throws FileAlreadyWritten
     * @throws FileNotWritten
     */
    private function checkFileWritten(bool $anticipation): void
    {
        if($anticipation === true && $this->isWritten === false) {
            throw new FileNotWritten();
        }
        elseif($anticipation === false && $this->isWritten === true) {
            throw new FileAlreadyWritten();
        }
    }


    /**
     * @param  string  $destinationFullPath
     * @param  mixed $content
     * @return iFile
     * @throws DirectoryDoesNotExist
     * @throws DirectoryNotWritable
     * @throws FileAlreadyWritten
     * @throws FileCanNotBeWritten
     * @throws FileDoesNotExist
     * @throws FileNotWritable
     * @throws FileNotWritten
     * @throws FileSystemException
     */
    public function write(string $destinationFullPath, $content): iFile
    {
        $this->checkFileWritten(false);

        $destinationFolder = dirname($destinationFullPath);

        if (!is_dir($destinationFolder)) {
            throw new DirectoryDoesNotExist($destinationFolder);
        }

        if (!is_writable($destinationFolder)) {
            throw new DirectoryNotWritable($destinationFolder);
        }

        if (is_file($destinationFullPath) && !is_writable($destinationFullPath)) {
            throw new FileNotWritable($destinationFullPath);
        }

        $response = file_put_contents($destinationFullPath, $content);

        if ($response === false) {
            throw new FileCanNotBeWritten();
        }

        $this->initialize($destinationFullPath);

        return $this;
    }


    /**
     * @param  string  $destinationFolder
     * @return iFile
     * @throws DirectoryDoesNotExist
     * @throws DirectoryNotWritable
     * @throws FileAlreadyWritten
     * @throws FileCanNotBeMoved
     * @throws FileDoesNotExist
     * @throws FileNotWritable
     * @throws FileNotWritten
     * @throws FileSystemException
     */
    public function move(string $destinationFolder): iFile
    {
        $this->checkFileWritten(true);

        if(mb_substr($destinationFolder,-1) !== "/")
            $destinationFolder = $destinationFolder.'/';

        if (!is_dir($destinationFolder)) {
            throw new DirectoryDoesNotExist($destinationFolder);
        }

        if (!is_writable($destinationFolder)) {
            throw new DirectoryNotWritable($destinationFolder);
        }

        if (is_file($destinationFolder.$this->getName()) && !is_writable($destinationFolder.$this->getName())) {
            throw new FileNotWritable($destinationFolder.$this->getName());
        }

        $response = rename($this->getFullPath(), $destinationFolder.$this->getName());

        if ($response === false) {
            throw new FileCanNotBeMoved();
        }

        $this->initialize($destinationFolder.$this->getName());

        return $this;
    }


    /**
     * @param  string  $newName
     * @return iFile
     * @throws DirectoryDoesNotExist
     * @throws DirectoryNotWritable
     * @throws FileAlreadyWritten
     * @throws FileCanNotBeRenamed
     * @throws FileDoesNotExist
     * @throws FileNotWritable
     * @throws FileNotWritten
     * @throws FileSystemException
     */
    public function rename(string $newName): iFile
    {
        $this->checkFileWritten(true);

        $newFullPath = $this->getFolder().'/'.$newName;

        if (!is_dir($this->getFolder())) {
            throw new DirectoryDoesNotExist($this->getFolder());
        }

        if (!is_writable($this->getFolder())) {
            throw new DirectoryNotWritable($this->getFolder());
        }

        if (is_file($newFullPath) && !is_writable($newFullPath)) {
            throw new FileNotWritable($newFullPath);
        }

        $response = rename($this->getFullPath(), $newFullPath);

        if ($response === false) {
            throw new FileCanNotBeRenamed();
        }

        $this->initialize($newFullPath);

        return $this;
    }


    /**
     * @return iFile
     * @throws FileAlreadyWritten
     * @throws FileCanNotBeDeleted
     * @throws FileNotWritten
     */
    public function delete(): iFile
    {
        $this->checkFileWritten(true);

        if (!is_file($this->getFullPath())) {
            throw new FileCanNotBeDeleted();
        }

        $response = unlink($this->getFullPath());

        if ($response === false) {
            throw new FileCanNotBeDeleted();
        }

        $this->isWritten = false;

        return $this;
    }


    /**
     * @param  string  $destinationFullPath
     * @return iFile
     * @throws DirectoryDoesNotExist
     * @throws DirectoryNotWritable
     * @throws FileAlreadyWritten
     * @throws FileCanNotBeWritten
     * @throws FileDoesNotExist
     * @throws FileNotWritable
     * @throws FileNotWritten
     * @throws FileSystemException
     */
    public function copy(string $destinationFullPath): iFile
    {
        $this->checkFileWritten(true);

        $destinationFolder = dirname($destinationFullPath);

        if (!is_dir($destinationFolder)) {
            throw new DirectoryDoesNotExist($destinationFolder);
        }

        if (!is_writable($destinationFolder)) {
            throw new DirectoryNotWritable($destinationFolder);
        }

        if (is_file($destinationFullPath) && !is_writable($destinationFullPath)) {
            throw new FileNotWritable($destinationFullPath);
        }

        $newLocalFile = new LocalFile();
        $newLocalFile->write($destinationFullPath, $this->getContent());

        return $this;
    }


    /**
     * @return string
     * @throws FileContentNotReadable
     */
    public function getContent(): string
    {
        $r = file_get_contents($this->getFullPath());

        if($r === false)
            throw new FileContentNotReadable();

        return $r;
    }
}