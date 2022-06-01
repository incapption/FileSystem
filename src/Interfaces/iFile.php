<?php

namespace Incapption\FileSystem\Interfaces;

interface iFile
{
    public function put(string $dest) : iFile;
    public function move(string $dest) : iFile;
    public function rename(string $newName) : iFile;
    public function delete() : iFile;
    public function copy(string $dest) : iFile;
    public function getName() : iFile;
    public function getSize() : iFile;
    public function getFullPath() : iFile;
    public function getMimeType() : iFile;
    public function getMaxSize() : iFile;
    public function getExtension() : iFile;
    public function setName(string $name) : iFile;
    public function setSize(int $size) : iFile;
    public function setFullPath(string $fullPath) : iFile;
    public function setMimeType(string $mimeType) : iFile;
    public function setMaxSize(int $maxSize) : iFile;
    public function setExtension(string $extension) : iFile;
}