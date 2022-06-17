<?php

namespace Dnd\DAO\Api5e;

use Dnd\DAO\DndApi;

class MagicSchools extends DndApi
{

    private $schema = "magic-schools";

    public function __construct()
    {
        parent::__construct($this->schema);
    }

    public function getAll()
    {

    }

    public function get(string $name)
    {
        return $this->getFromAPI($name);
    }

    


}