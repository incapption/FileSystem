<?php

namespace Incapption\FileSystem\Exceptions;

use Throwable;

class FileCanNotBeDeleted extends FileSystemException
{
    public function __construct($message = 'the file can not been deleted. It does not exist and/or is not accessible', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}