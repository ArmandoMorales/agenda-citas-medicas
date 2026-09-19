<?php

namespace App\Exceptions;

use Exception;

class TransicionEstadoInvalidaException extends Exception
{
    public function __construct(string $message = 'La cita no puede cambiar a ese estado desde su estado actual.')
    {
        parent::__construct($message, 409);
    }
}
