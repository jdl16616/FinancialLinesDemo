<?php

namespace App\Entity\Trait\Column;

use Doctrine\ORM\Mapping as ORM;

trait Amount
{
    #[ORM\Column(type: "float")]
    private $amount;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}