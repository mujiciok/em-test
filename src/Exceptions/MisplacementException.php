<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class MisplacementException extends Exception
{
    protected $message = 'Can not place a box without direction';
}