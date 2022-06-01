<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class DirectoryDoesNotExist extends FileSystemException
{
    public function __construct($directory, $code = 0, Throwable $previous = null)
    {
        parent::__construct('the directory '.$directory.' does not exist', $code, $previous);
    }
}