<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileDoesNotExist extends FileSystemException
{
    public function __construct($fullPath, $code = 0, Throwable $previous = null)
    {
        parent::__construct('file '.$fullPath.' does not exist', $code, $previous);
    }
}