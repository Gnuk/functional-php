<?php

namespace Gnuk\Test\Functional;

use Closure;
use Gnuk\Functional\EmptyValueException;
use Gnuk\Functional\Optional;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class OptionalTest extends TestCase
{
    private Optional $valuated;
    private Optional $empty;
    private Closure $intToString;
    private static mixed $VALUE = 42;
    private static mixed $OTHER_VALUE = 23;
    private Closure$otherPredicate;

    /**
     * @throws ExpectationFailedException
     */
    static function assertThatThrown(Closure $expectation, Closure $throwable) {
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
        $this->valuated = Optional::of(self::$VALUE);
        $this->empty = Optional::empty();
        $this->intToString = fn(int $value) => strval($value);
        $this->otherPredicate = fn() => self::$OTHER_VALUE;
    }

    /**
     * @test
     */
    function shouldGetWrappedValue() {
        self::assertSame(self::$VALUE, $this->valuated->get());
        self::assertSame(self::$VALUE, $this->valuated->orElse(self::$OTHER_VALUE));
        self::assertSame(self::$VALUE, $this->valuated->orElseGet($this->otherPredicate));
    }

    /**
     * @test
     */
    function shouldBePresentWhenValuated() {
        self::assertTrue($this->valuated->isPresent());
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
        self::assertFalse($this->empty->isPresent());
    }

    /**
     * @test
     */
    function shouldGetOtherWhenEmpty() {
        self::assertSame(self::$OTHER_VALUE, $this->empty->orElse(self::$OTHER_VALUE));
        self::assertSame(self::$OTHER_VALUE, $this->empty->orElseGet($this->otherPredicate));
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

    /**
     * @test
     */
    function shouldMakeAnActionWhenPresent() {
        $register = 0;

        $this->valuated->ifPresent(function($value) use (&$register){
            $register = $value;
        });

        self::assertSame(self::$VALUE, $register);
    }

    /**
     * @test
     */
    function shouldNotMakeAnActionWhenEmpty() {
        $register = 0;

        $this->empty->ifPresent(function($value) use (&$register){
            $register = $value;
        });

        self::assertSame(0, $register);
    }

    /**
     * @test
     */
    function shouldLaunchActionWhenPresentOnPresentOrElse() {
        $register = 0;
        $action = function ($value) use (&$register) {
            $register = $value;
        };
        $otherAction = function () use (&$register) {
            $register = self::$OTHER_VALUE;
        };

        $this->valuated->ifPresentOrElse($action, $otherAction);

        self::assertSame(self::$VALUE, $register);
    }

    /**
     * @test
     */
    function shouldLaunchOtherActionWhenEmptyOnPresentOrElse() {
        $register = 0;
        $action = function ($value) use (&$register) {
            $register = $value;
        };
        $otherAction = function () use (&$register) {
            $register = self::$OTHER_VALUE;
        };

        $this->empty->ifPresentOrElse($action, $otherAction);

        self::assertSame(self::$OTHER_VALUE, $register);
    }

    /**
     * @test
     */
    function shouldFilterToEmptyWhenPredicateIsFalse() {
        self::assertTrue($this->valuated->filter(fn($value) => $value === self::$OTHER_VALUE)->isEmpty());
    }

    /**
     * @test
     */
    function shouldFilterToSameWhenPredicateIsTrue() {
        self::assertSame(self::$VALUE, $this->valuated->filter(fn($value) => $value === self::$VALUE)->get());
    }

    /**
     * @test
     */
    function shouldFilterToEmptyWhenAlreadyEmpty() {
        self::assertTrue($this->empty->filter(fn($value) => $value === self::$VALUE)->isEmpty());
    }
}
