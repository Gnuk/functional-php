<?php

namespace Gnuk\Functional;

class Stream {

    private function __construct(private array $array) {}

    static function of(array $array): Stream
    {
        return new Stream($array);
    }

    function toArray(): array
    {
        return $this->array;
    }

    function map(\Closure $transform): Stream
    {
        return Stream::of(array_map($transform, $this->array));
    }

    function chain(\Closure $transform): Stream
    {
        $transformWithArray = fn(mixed $element) => $transform($element)->toArray();
        return Stream::of(array_merge(...array_map($transformWithArray, $this->array)));
    }
}
