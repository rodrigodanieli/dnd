<?php

namespace Dnd\DAO;

use GuzzleHttp\Client;
use Sohris\Core\Utils;
use Throwable;

class DndApi
{
    private $client;

    private $config;

    private $schema = "";

    public function __construct(string $schema)
    {
        $this->schema = $schema;
        echo $schema;
        $this->load();
    }


    private function load()
    {
        $this->config = Utils::getConfigFiles('dndapi');
        
        $url = $this->config['api_url'] . "/" . $this->schema;
        echo $url . PHP_EOL;
        $this->client = new Client([
            "base_uri" => $url
        ]);
    }

    public function getFromAPI(string $url, array $body = [])
    {

        try {
            $a = $this->client->request("GET", $this->schema . "/" .$url, [
                'headers' => ["Accept" => "application/json", "Content-Type" => "application/json"],
                'body' => json_encode($body)
            ]);

            return json_decode($a->getBody()->getContents(), true);
        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
