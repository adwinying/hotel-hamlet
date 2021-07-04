<?php

namespace App\Exceptions;

use Exception;

class RoomUnavailableException extends Exception
{
    protected string $customMessage = 'There are no rooms that are available for booking.';

    public function __construct()
    {
        parent::__construct($this->customMessage);
    }
}
