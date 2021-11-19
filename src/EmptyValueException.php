<?php

namespace Gnuk\Functional;

class EmptyValueException extends \Exception
{
    static function for(string $kind): EmptyValueException
    {
        return new EmptyValueException($kind . ' can\'t have value');
    }
}
