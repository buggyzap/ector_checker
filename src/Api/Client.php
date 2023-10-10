<?php

namespace Ector\Checker\Api;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private static $instance = null;
    private $client;
    public const BASE_URL = "https://ector.store/api/";

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => self::BASE_URL,
            'timeout' => 5,
        ]);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $uri)
    {
        return $this->client->get($uri);
    }

    public function post(string $uri, array $data)
    {
        return $this->client->post($uri, $data);
    }

    public function put(string $uri, array $data)
    {
        return $this->client->put($uri, $data);
    }

    public function delete(string $uri)
    {
        return $this->client->delete($uri);
    }
}