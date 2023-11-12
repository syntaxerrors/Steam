<?php

namespace Syntax\SteamApi\Exceptions;

class UnrecognizedId extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct(string $message, int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 
