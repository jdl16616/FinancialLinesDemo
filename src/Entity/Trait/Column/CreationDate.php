<?php

namespace App\Entity\Trait\Column;

use Doctrine\ORM\Mapping as ORM;

trait CreationDate
{
    #[ORM\Column(type: "date")]
    private $creationDate;

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
}