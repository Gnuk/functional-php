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

    function orElseGet(\Closure $other): mixed
    {
        return $other();
    }

    function ifPresent(\Closure $action): void
    {
        // Nothing to do because it's empty
    }

    function ifPresentOrElse(\Closure $action, \Closure $otherAction): void
    {
        $otherAction();
    }

    function isPresent(): bool
    {
        return false;
    }

    function filter(\Closure $filter): Optional
    {
        return $this;
    }
}
