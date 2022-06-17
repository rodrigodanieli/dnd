<?php

namespace Dnd;

use Dnd\DAO\Api5e\Spells;
use Dnd\DAO\Dnd\Spells as DndSpells;
use Dnd\DAO\Translate;

class Running
{

    public function run(){
        $school = "conjuration";
        $spells_api = new Spells;
        $spells_db = new DndSpells;
        $all_magics = $spells_api->getFromSchool($school);
        echo "Magic Count - $all_magics[count]" . PHP_EOL;
        foreach($all_magics['results'] as $magic)
        {
            echo "Trying $magic[name]" . PHP_EOL;
            $config = $spells_api->get($magic['index']);
            if(!$config)
                echo "Magic not exists" . PHP_EOL;
            $config['magic_school_id'] = 2;
            if($spells_db->updateSchool($config))
                echo "Success" . PHP_EOL;
    
        }
    }
}