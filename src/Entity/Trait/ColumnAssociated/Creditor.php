<?php

namespace App\Entity\Trait\ColumnAssociated;

// Entity
use App\Entity\Company as EntityCompany;
use Doctrine\ORM\Mapping as ORM;

// Vendor

trait Creditor
{
    //#[ORM\Column(type: "integer")]
    #[ORM\ManyToOne(targetEntity: EntityCompany::class)]
    #[ORM\JoinColumn(name: "creditor_id", referencedColumnName: "id")]
    private $creditor;

    /**
     * @return EntityCompany
     */
    public function getCreditor():EntityCompany
    {
        return $this->creditor;
    }

    /**
     * @param EntityCompany $creditor
     */
    public function setCreditor($creditor):void
    {
        $this->creditor = $creditor;
    }
}