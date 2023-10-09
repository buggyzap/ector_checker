<?php

namespace Ector\Checker;

use Ector\Checker\Api\Client;

class Checker
{
    public const CHECK_DIFF = 60;

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
        return \Configuration::get("_ECTOR_LASTCHECK");
    }

    public function getKey()
    {
        return \Configuration::get("_ECTOR_APIKEY");
    }

    public function getShopDomain()
    {
        // parse url from _PS_BASE_URL_
        $url = parse_url(_PS_BASE_URL_);

        // return host
        return $url['host'];
    }

    /**
     * Perform a health check on the API
     *
     * @return bool
     */
    public function healthCheck(\AdminModulesController $controller)
    {
        $key = $this->getKey();
        if (! $key) {
            return false;
        }

        $api = Client::getInstance()->get("license/verify/$key");

        $code = $api->getStatusCode();
        if ($code !== 200) {
            return false;
        }

        $body = $api->getBody();
        $body = json_decode($body, true);

        if (! $body["valid"] === true || ! $body["website"] === $this->getShopDomain()) {
            $controller->errors[] = "Your API key is invalid. Please check your configuration.";
        }
    }
}
