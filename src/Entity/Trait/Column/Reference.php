<?php

namespace App\Entity\Trait\Column;

use Doctrine\ORM\Mapping as ORM;

trait Reference
{
    #[ORM\Column(type: "string", length: 100)]
    private $reference;

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }
}