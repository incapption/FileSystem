<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class DirectoryNotWritable extends FileSystemException
{
    public function __construct($directory, $code = 0, Throwable $previous = null)
    {
        parent::__construct('the directory '.$directory.' is not writable.', $code, $previous);
    }
}