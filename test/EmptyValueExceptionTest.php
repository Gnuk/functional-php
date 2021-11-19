<?php

namespace Gnuk\Test\Functional;

use Gnuk\Functional\EmptyValueException;
use PHPUnit\Framework\TestCase;

class EmptyValueExceptionTest extends TestCase
{

    /**
     * @test
     */
    function shouldGetMessage() {
        self::assertSame('Kind can\'t have value', EmptyValueException::for('Kind')->getMessage());
    }
}
