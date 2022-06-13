<?php

namespace Incapption\FileSystem\Interfaces;

interface FileInterface
{
    public function allowMimeType(array $mime_types): FileInterface;

    public function disallowMimeType(array $mime_types): FileInterface;

    public function __move(string $dest): FileInterface;

    public function __rename(string $new_name): FileInterface;

    public function __copy(string $dest): FileInterface;

    public function __delete(): bool;

    public function setRandomName(string $prefix = ''): FileInterface;

    public function getFullPath(): string;

    public function getFileName(): string;

    public function getFileSize(): int;

    public function getFileExtension(): string;

    public function getMimeType(): string;

    public function getDirectoryName(): string;

    public function toArray(): array;
}