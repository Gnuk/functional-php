<?php

namespace Gnuk\Functional;

abstract class Optional
{

    static function empty(): OptionalEmpty
    {
        return new OptionalEmpty();
    }

    static function of(mixed $value): Optional
    {
        return new OptionalValue($value);
    }

    public static function ofNullable(mixed $value): Optional
    {
        if (is_null($value)) {
            return new OptionalEmpty();
        }
        return new OptionalValue($value);
    }

    abstract function isEmpty(): bool;

    /**
     * @throws EmptyValueException
     */
    abstract function get(): mixed;

    abstract function map(\Closure $transform): Optional;

    abstract function orElse(mixed $other): mixed;
}
