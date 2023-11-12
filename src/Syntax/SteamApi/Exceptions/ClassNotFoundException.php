<?php

namespace Syntax\SteamApi\Exceptions;

class ClassNotFoundException extends \Exception
{
    public function __construct($class)
    {
        parent::__construct('The called class [' . $class . '] does not exist.');
    }
}
