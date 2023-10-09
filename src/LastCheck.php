<?php

namespace Ector\Checker;

class LastCheck
{
    private $last;

    public function __construct()
    {
        $this->last = (int)\Configuration::get("_ECTOR_LASTCHECK");
    }

    public function getLastCheck(): int
    {
        return $this->last;
    }

    public function setLastCheck(int $last)
    {
        $this->last = $last;
    }
}
