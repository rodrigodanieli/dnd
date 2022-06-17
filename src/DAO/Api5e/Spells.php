<?php

namespace Dnd\DAO\Api5e;

use Dnd\DAO\DndApi;

class Spells extends DndApi
{

    private $schema = "spells";

    public function __construct()
    {
        parent::__construct($this->schema);
    }

    public function get(string $name)
    {
        return $this->getFromAPI($name);
    }

    public function getFromSchool(string $school_name)
    {
        return $this->getFromAPI('', ["school" => $school_name]);
    }

    


}