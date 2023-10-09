<?php

namespace Ector\Checker;

class LastCheck
{
    private $last;

    public function __construct()
    {
        $this->last = (int)\Configuration::get("_ECTOR_LASTCHECK", null, null, null, 0);
    }

    public function getLastCheck(): int
    {
        return $this->last;
    }

    public function setLastCheck(int $last)
    {
        $this->last = $last;
    }

    public function updateLastCheckRemote()
    {
        \Configuration::updateValue("_ECTOR_LASTCHECK", time());
    }
}
