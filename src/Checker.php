<?php

namespace Ector\Checker;

class Checker
{

    public function getKey()
    {
        return Configuration::getValue("_ECTOR_APIKEY");
    }

    public function check()
    {
        return true;
    }
}
