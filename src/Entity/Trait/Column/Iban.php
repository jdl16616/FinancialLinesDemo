<?php

namespace App\Entity\Trait\Column;
use Doctrine\ORM\Mapping as ORM;

trait Iban
{
    #[ORM\Column(type: "string", length: 100)]
    private $iban;

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }



}