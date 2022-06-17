<?php

namespace Dnd\DAO;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PDOException;
use Psr\Http\Message\ResponseInterface;
use Sohris\Core\Utils;
use Throwable;

class Translate
{
    private static $client;

    private static $config;

    public function __construct()
    {
    }

    private static function load()
    {
        if (!self::$config) {
            self::$config = Utils::getConfigFiles('ibm');
            self::$client = new Client([
                "base_uri" => self::$config['api_url']
            ]);
        }
    }

    public static function getTranslate(string $text, string $locale = 'en-pt')
    {
        self::load();

        try {
            $a = self::$client->request("POST", '/v3/translate?version=2018-05-01', [
                'headers' => ["Accept" => "application/json", "Content-Type" => "application/json"],
                'auth' => ['apikey', self::$config['api_key']],
                'body' => json_encode(["text" => $text, "model_id" => $locale])
            ]);

            $translate = json_decode($a->getBody()->getContents(), true);
            return $translate['translations'][0]['translation'];
        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public static function getTranslateAsync(string $text, string $locale = 'en-pt')
    {
        return;
        self::load();
        try {
            echo $text . PHP_EOL;
            return self::$client->requestAsync("POST", '/v3/translate?version=2018-05-01', [
                'headers' => ["Accept" => "application/json", "Content-Type" => "application/json"],
                'auth' => ['apikey', self::$config['api_key']],
                'body' => json_encode(["text" => $text, "model_id" => $locale])
            ])->then(
                function (ResponseInterface $response) {
                    return json_decode($response->getBody()->getContents(), true);
                },
                function (RequestException $e) {
                    echo $e->getMessage() . "\n";
                    echo $e->getRequest()->getMethod();
                }
            );
        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }


    public static function insertNewTranslation($text, $locale, $type)
    {
        try {
            $dao = new Database;

            if (is_array($text)) {
                $translate = [];
                foreach ($text as $t) {
                    $translate[] = self::getTranslate($t, $locale);
                }
            } else
                $translate = self::getTranslate($text, $locale);


            $texts = json_encode(["pt_br" => $translate, "en_us" => $text]);

            $sql = "INSERT INTO dnd.dnd_locale (uuid, type, texts) VALUES (UUID(), :type, :texts)";

            $stm = $dao->prepare($sql);
            $stm->bindValue("type", $type);
            $stm->bindValue("texts", $texts);
            $stm->execute();

            $id = $dao->lastInsertId();

            $sql = "SELECT uuid FROM dnd.dnd_locale WHERE id = $id";

            return $dao->query($sql)->fetch(\PDO::FETCH_ASSOC)['uuid'];
        } catch (PDOException $e) {
            echo $e->getMessage() . PHP_EOL;
        }catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
