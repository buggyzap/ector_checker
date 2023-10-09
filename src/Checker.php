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

    public function getKey()
    {
        return \Configuration::get("_ECTOR_APIKEY");
    }

    public function getShopDomain()
    {
        $url = parse_url(_PS_BASE_URL_);

        return $url['host'];
    }

    public function createDatabaseError()
    {
        \Configuration::updateValue("_ECTOR_ERROR", 1);
    }

    public function getDatabaseError()
    {
        return (int)\Configuration::get("_ECTOR_ERROR");
    }

    public function removeDatabaseError()
    {
        \Configuration::deleteByName("_ECTOR_ERROR");
    }

    /**
     * Perform a health check on the API
     *
     * @return bool
     */
    public function healthCheck(\AdminModulesController $controller)
    {
        $key = $this->getKey();
        $lastCheck = new LastCheck();

        if (!$this->checkHasToBeRun($lastCheck) && $this->getDatabaseError() !== 1) {
            return true;
        }

        if (!$key) {
            return false;
        }

        $api = Client::getInstance()->get("license/verify/$key");

        $code = $api->getStatusCode();
        if ($code !== 200) {
            return false;
        }

        $body = $api->getBody();
        $body = json_decode($body, true);

        if (!$body["valid"] === true || !$body["website"] === $this->getShopDomain()) {
            $controller->errors[] = "Your Ector installation is expired, not valid or corrupted. Please contact our support at help@ector.store if you think that is a mistake.";

            return false;
        }

        $lastCheck->updateLastCheckRemote();
        $this->removeDatabaseError();

        return true;
    }
}
