<?php

namespace Gnuk\Test\Functional;

use Gnuk\Functional\EmptyValueException;
use Gnuk\Functional\Optional;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class OptionalTest extends TestCase
{
    private Optional $valuated;
    private Optional $empty;
    private \Closure $intToString;

    /**
     * @throws ExpectationFailedException
     */
    static function assertThatThrown(\Closure $expectation, \Closure $throwable) {
        try {
            $throwable();
        } catch(\Exception $exception) {
            $expectation($exception);
            return;
        }
        throw new ExpectationFailedException('You expect to throw but nothing thrown.');
    }

    /**
     * @before
     */
    function factories(): void {
        $this->valuated = Optional::of(42);
        $this->empty = Optional::empty();
        $this->intToString = fn(int $value) => strval($value);
    }

    /**
     * @test
     */
    function shouldGetWrappedValue() {
        self::assertSame(42, $this->valuated->get());
        self::assertSame(42, $this->valuated->orElse(23));
    }

    /**
     * @test
     */
    function shouldBePresentWhenValuated() {
        self::assertFalse($this->valuated->isEmpty());
    }

    /**
     * @test
     */
    function shouldMap() {
        self::assertSame('42', $this->valuated->map($this->intToString)->get());
    }

    /**
     * @test
     */
    function shouldBeEmptyWhenEmpty() {
        self::assertTrue($this->empty->isEmpty());
        self::assertSame(23, $this->empty->orElse(23));
    }

    /**
     * @test
     */
    function shouldMapToEmptyWhenEmpty() {
        self::assertTrue($this->empty->map($this->intToString)->isEmpty());
    }

    /**
     * @test
     */
    function shouldNotGetForEmpty() {
        self::assertThatThrown(
            function ($exception) {
                self::assertInstanceOf(EmptyValueException::class, $exception);
                self::assertStringContainsString('Empty optional', $exception->getMessage());
            },
            fn() => $this->empty->get());
    }

    /**
     * @test
     */
    function shouldBeEmptyFromNullable() {
        self::assertTrue(Optional::ofNullable(null)->isEmpty());
    }

    /**
     * @test
     */
    function shouldBeValuatedFromNonNullValue() {
        self::assertSame(0, Optional::ofNullable(0)->get());
    }
}
