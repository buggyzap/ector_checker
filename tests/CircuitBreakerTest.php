<?php

namespace Ector\Checker\Tests;

use Ector\Checker\CircuitBreaker;
use PHPUnit\Framework\TestCase;

class CircuitBreakerTest extends TestCase
{
    public function testAllowRequestWhenCircuitIsClosed()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $this->assertTrue($circuitBreaker->allowRequest());
    }

    public function testAllowRequestWhenCircuitIsOpen()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $circuitBreaker->handleFailure();
        $circuitBreaker->handleFailure();
        $circuitBreaker->handleFailure();
        $this->assertFalse($circuitBreaker->allowRequest());
    }

    public function testHandleSuccessResetsFailuresAndClosesCircuit()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $circuitBreaker->handleFailure();
        $circuitBreaker->handleSuccess();
        $this->assertEquals(0, $circuitBreaker->getFailures());
        $this->assertFalse($circuitBreaker->getIsOpen());
    }

    public function testHandleFailureIncrementsFailuresAndOpensCircuit()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $circuitBreaker->handleFailure();
        $this->assertEquals(1, $circuitBreaker->getFailures());
        $this->assertFalse($circuitBreaker->getIsOpen());
        $circuitBreaker->handleFailure();
        $this->assertEquals(2, $circuitBreaker->getFailures());
        $this->assertFalse($circuitBreaker->getIsOpen());
        $circuitBreaker->handleFailure();
        $this->assertEquals(3, $circuitBreaker->getFailures());
        $this->assertTrue($circuitBreaker->getIsOpen());
    }

    public function testUpdateLastTry()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $circuitBreaker->updateLastTry();
        sleep(1);
        $this->assertGreaterThan($circuitBreaker->getLastTry(), time());
    }

    public function testResetFailures()
    {
        $circuitBreaker = new CircuitBreaker(0, time());
        $circuitBreaker->incrementFailures();
        $circuitBreaker->incrementFailures();
        $circuitBreaker->resetFailures();
        $this->assertEquals(0, $circuitBreaker->getFailures());
    }
}
