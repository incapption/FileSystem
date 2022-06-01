<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileNotWritable extends FileSystemException
{
    public function __construct($fullPath, $code = 0, Throwable $previous = null)
    {
        parent::__construct('the file '.$fullPath.' exists but is not writable.', $code, $previous);
    }
}