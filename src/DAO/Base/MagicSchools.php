<?php
namespace Dnd\DAO\Dnd;

use Dnd\DAO\Database;

class MagicSchools extends Database
{
    

    public function getByName(string $name)
    {
        $query = "SELECT * FROM dnd_magic_schools WHERE name = ?";
        $stm = $this->prepare($query);
        $stm->bindParam(1,$name);
        $stm->execute();
        return $stm->fetch(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM dnd_magic_schools WHERE id = ?";
        $stm = $this->prepare($query);
        $stm->bindParam(1,$name);
        $stm->execute();
        return $stm->fetch(\PDO::FETCH_ASSOC);
    }

}