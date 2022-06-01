<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileContentNotReadable extends FileSystemException
{
    public function __construct($message = 'the file contents is not readable', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}