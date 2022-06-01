<?php

namespace Incapption\FileSystem\Interfaces;

interface iFile
{
    public function write(string $destinationFullPath, $content) : iFile;
    public function move(string $destinationFolder) : iFile;
    public function rename(string $newName) : iFile;
    public function delete() : iFile;
    public function copy(string $destinationFullPath) : iFile;
    public function getName() : string;
    public function getSize() : int;
    public function getFullPath() : string;
    public function getFolder(): string;
    public function getMimeType() : string;
    public function getExtension() : string;
    public function getLastModified() : int;
    public function toArray(): array;
    public function toJson(): string;
    public function getContent() : string;
}