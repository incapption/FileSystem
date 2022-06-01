<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileNotWritten extends FileSystemException
{
    public function __construct($message = 'the file has not been written yet.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}