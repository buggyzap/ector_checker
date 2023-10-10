<?php

namespace Ector\Checker;

class CircuitBreaker
{
    private $isOpen = false;
    private $failures = 0;
    private $lastTry = 0;
    public const MAX_FAILURES = 3;
    public const RETRY_TIMEOUT = 120;

    public const CACHE_KEY = "_ECTOR_CIRCUIT_FAIL";
    public const RETRY_KEY = "_ECTOR_CIRCUIT_RETRY";

    public function __construct()
    {
        $this->failures = (int) \Configuration::get(self::CACHE_KEY, null, null, null, 0);
        $this->lastTry = (int) \Configuration::get(self::RETRY_KEY, null, null, null, time());
        $this->isOpen = $this->failures >= self::MAX_FAILURES;
    }

    public function updateLastTry()
    {
        \Configuration::updateValue(self::RETRY_KEY, time());
    }

    public function incrementFailures()
    {
        $this->failures++;
        \Configuration::updateValue(self::CACHE_KEY, $this->failures);
    }

    public function resetFailures()
    {
        $this->failures = 0;
        \Configuration::updateValue(self::CACHE_KEY, $this->failures);
    }

    public function allowRequest(): bool
    {
        // reset failures if last try was more than RETRY_TIMEOUT seconds ago and close the circuit
        if (time() - $this->lastTry > self::RETRY_TIMEOUT) {
            $this->resetFailures();
            $this->isOpen = false;
        }
        if ($this->isOpen) {
            return false;
        }

        return true;
    }

    public function handleSuccess()
    {
        $this->resetFailures();
        $this->isOpen = false;
    }

    public function handleFailure()
    {
        $this->incrementFailures();
        $this->updateLastTry();
        if ($this->failures >= self::MAX_FAILURES) {
            $this->isOpen = true;
        }
    }
}