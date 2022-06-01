<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileCanNotBeWritten extends FileSystemException
{
    public function __construct($message = 'the file can not been written. It is empty and/or not accessible.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}