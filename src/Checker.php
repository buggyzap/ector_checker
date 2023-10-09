<?php

namespace Ector\Checker;

use Ector\Checker\Api\Client;

class Checker
{

    const CHECK_DIFF = 60;

    public function checkHasToBeRun(LastCheck $lastCheck)
    {
        $now = time();
        $diff = $now - $lastCheck->getLastCheck();

        if ($diff > self::CHECK_DIFF) {
            return true;
        }

        return false;
    }

    public function getLastCheck()
    {
        return \Configuration::getValue("_ECTOR_LASTCHECK");
    }

    public function getKey()
    {
        return \Configuration::getValue("_ECTOR_APIKEY");
    }

    /**
     * Perform a health check on the API
     * 
     * @return bool
     */
    public function healthCheck()
    {
        $key = $this->getKey();
        if (!$key) {
            return false;
        }
        $api = Client::getInstance()->get("license/verify/$key");
        var_dump($api);
    }
}
