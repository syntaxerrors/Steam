<?php

namespace Syntax\SteamApi\Exceptions;

class ApiCallFailedException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct(string $message, int $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 
