<?php

namespace App\Entity\Trait\Column;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id as ORM_id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;

trait Id
{

    #[ORM_id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(type: "integer")]
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}