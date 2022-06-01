<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileAlreadyWritten extends FileSystemException
{
    public function __construct($message = 'the file has already been written', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}