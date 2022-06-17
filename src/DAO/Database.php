<?php

namespace Dnd\DAO;

use PDO;
use Sohris\Core\Utils;

use function PHPSTORM_META\type;

class Database extends PDO
{

    public function __construct()
    {
        $configs = Utils::getConfigFiles('database');

        parent::__construct("mysql:host=$configs[host];dbname=$configs[base]", $configs['user'], $configs['pass']);
    }

    public function runQuery(string $query, array $param = [])
    {

        try {
            $stmt = $this->prepare($query);

            foreach ($param as $key => $value) {
                echo "$key => $value - " . PHP_EOL;
                if ($value == "NULL" || trim($value) == "") {
                    $stmt->bindValue($key+1, $value, \PDO::PARAM_NULL);
                } else if (
                    $value == "False" ||
                    $value == "false" ||
                    $value == "True" ||
                    $value == "true" ||
                    $value === true ||
                    $value === false
                ) {
                    $stmt->bindValue($key+1, $value, \PDO::PARAM_BOOL);
                } else {
                    $stmt->bindValue($key+1, $value);
                }
            }
            //$stmt->debugDumpParams();
            $result = $stmt->execute();
            if($result)
                return $this->lastInsertId();
            return false;
                
        } catch (\PDOException $e) {
            
                echo $e->getMessage() . PHP_EOL;
            
        }
    }
}