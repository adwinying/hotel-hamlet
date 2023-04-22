<?php

namespace App\Exceptions;

use Exception;

class DataInvalidException extends Exception
{
    protected string $customMessage = 'Data is invalid';

    public function __construct()
    {
        parent::__construct($this->customMessage);
    }
}
