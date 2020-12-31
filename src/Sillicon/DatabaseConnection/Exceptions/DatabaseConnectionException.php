<?php

namespace Sillicon\DatabaseConnection\Exceptions;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    public function __construct( string $message, int $code )
    {
        $this->message = $message;
        $this->code    = $code;
    }
}
