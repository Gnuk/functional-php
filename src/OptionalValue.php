<?php

namespace Gnuk\Functional;

class OptionalValue extends Optional
{
    protected function __construct(private mixed $value)
    {
        // Don't let create empty optional directly
    }

    function get(): mixed
    {
        return $this->value;
    }

    function map(\Closure $transform): Optional
    {
        return Optional::of($transform($this->value));
    }

    function isEmpty(): bool
    {
        return false;
    }

    function orElse(mixed $other): mixed
    {
        return $this->value;
    }

    function orElseGet(\Closure $other): mixed
    {
        return $this->value;
    }

    function ifPresent(\Closure $action): void
    {
        $action($this->value);
    }

    function ifPresentOrElse(\Closure $action, \Closure $otherAction): void
    {
        $action($this->value);
    }
}
