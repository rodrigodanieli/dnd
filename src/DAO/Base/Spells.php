<?php

namespace Dnd\DAO\Dnd;

use Dnd\DAO\Database;
use Dnd\DAO\Translate;

class Spells extends Database
{
    const DEFAULT_CONFIG = ["_id", "index", "magic_school_id", "name", "desc", "range", "components", "higher_level", "material", "ritual", "duration", "concentration", "casting_time", "level", "school", "classes", "subclasses", "url"];

    public function validSpellExistis($name)
    {
        $query = "SELECT * FROM dnd.dnd_spells where name = ?";
        $stm = $this->prepare($query);
        $stm->bindValue(1, $name);
        $stm->execute();
        echo $stm->rowCount();
        return $stm->rowCount() == 0;
    }

    public function addNewSpell(array $configs)
    {

        if (!$this->validSpellExistis($configs['index'])) {
            echo "Magic alreary exists! $configs[name] " . PHP_EOL;
            return;
        }
        $extra = [];
        $configs['ritual'] = $configs['ritual'] === false ? "false" : "true";
        $configs['concentration'] = $configs['concentration'] === false ? "false" : "true";
        $desc_id = "";
        $m_id = '';
        $h_id = '';
        $name_id = "";

        foreach ($configs as $name => $config) {
            if (!in_array($name, self::DEFAULT_CONFIG)) {
                $extra[$name] = $config;
            }
        }

        $name_id = Translate::insertNewTranslation($configs['name'], "en-pt", "spell_name");

        if (!empty($configs['desc']))
            $desc_id = Translate::insertNewTranslation($configs['desc'], "en-pt", "spell_description");

        if (!empty($configs['higher_level']))
            $h_id = Translate::insertNewTranslation($configs['higher_level'], "en-pt", "spell_higher_level");

        if (!empty($configs['material']))
            $m_id = Translate::insertNewTranslation($configs['material'], "en-pt", "spell_materials");

        $params = [
            $configs['index'],
            $configs['magic_school_id'],
            $name_id,
            $desc_id,
            $h_id,
            $configs['level'],
            $configs['range'],
            implode(",", $configs['components']),
            $m_id,
            $configs['ritual'],
            $configs['duration'],
            $configs['concentration'],
            $configs['casting_time'],
            empty($extra) ? 'NULL' : json_encode($extra)
        ];
        $query = "INSERT INTO dnd.dnd_spells VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        return $this->runQuery($query, $params);
    }

    public function updateSchool(array $config)
    {

        $school = $config['school']['index'];
        $id = $config['index'];

        $query = "UPDATE dnd.dnd_spells set magic_school = (SELECT id FROM dnd_magic_schools WHERE name = ?) WHERE name = ?";

        $stm = $this->prepare($query);
        $stm->bindValue(1, $school);
        $stm->bindValue(2, $id);

        $stm->execute();
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM dnd_magic_schools WHERE id = ?";
        $stm = $this->prepare($query);
        $stm->bindParam(1, $name);
        $stm->execute();
        return $stm->fetch(\PDO::FETCH_ASSOC);
    }
}
