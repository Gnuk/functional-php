<?php

namespace Gnuk\Test\Functional;

use Gnuk\Functional\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{

    /**
     * @test
     */
    function shouldWrap() {
        self::assertSame([1,2,3], Stream::of([1,2,3])->toArray());
    }

    /**
     * @test
     */
    function shouldMap() {
        self::assertSame(['1','2','3'], Stream::of([1,2,3])->map(fn(int $element) => strval($element))->toArray());
    }

    /**
     * @test
     */
    function shouldChain() {
        self::assertSame([1,2,3], Stream::of(['1,2', '3'])
            ->chain(
                fn(string $element) => Stream::of(explode(',', $element))
                    ->map(fn(string $string) => intval($string))
            )
            ->toArray());
    }
}
