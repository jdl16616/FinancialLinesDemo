<?php

namespace App\Entity\Trait\Column;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

trait DueDate
{
    #[ORM\Column(type: "date")]
    private $dueDate;

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate): void
    {
        $this->dueDate = $dueDate;
    }


}