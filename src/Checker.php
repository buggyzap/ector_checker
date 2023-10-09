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
        // emulate check log
        file_put_contents("./ector.log", "check\n", FILE_APPEND);

        return true;
    }
}
