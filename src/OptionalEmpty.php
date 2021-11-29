<?php

namespace Gnuk\Functional;

class OptionalEmpty extends Optional
{
    protected function __construct()
    {
        // Don't let create empty optional directly
    }

    function get(): mixed
    {
        throw EmptyValueException::for('Empty optional');
    }

    function map(\Closure $transform): Optional
    {
        return $this;
    }

    function isEmpty(): bool
    {
        return true;
    }

    function orElse(mixed $other): mixed
    {
        return $other;
    }
}
