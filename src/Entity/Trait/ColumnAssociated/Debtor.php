<?php

namespace App\Entity\Trait\ColumnAssociated;

use App\Entity\Company as EntityCompany;
use Doctrine\ORM\Mapping as ORM;

trait Debtor
{
    #[ORM\ManyToOne(targetEntity: EntityCompany::class)]
    #[ORM\JoinColumn(name: "debtor_id", referencedColumnName: "id")]
    private $debtor;

    /**
     * @return EntityCompany
     */
    public function getDebtor():EntityCompany
    {
        return $this->debtor;
    }

    /**
     * @param EntityCompany $debtor
     */
    public function setDebtor($debtor):void
    {
        $this->debtor = $debtor;
    }


}