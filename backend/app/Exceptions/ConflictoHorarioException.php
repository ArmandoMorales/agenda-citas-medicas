<?php

namespace App\Exceptions;

use Exception;

class ConflictoHorarioException extends Exception
{
    public function __construct(string $message = 'El doctor ya tiene una cita activa que se solapa con ese horario.')
    {
        parent::__construct($message, 409);
    }
}
