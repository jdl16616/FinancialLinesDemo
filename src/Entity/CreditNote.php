<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Amount as EntityTraitAmount;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Statement as EntityTraitStatement;

// Interface
use App\Entity\Interface\Export\Csv as EntityInterfaceExportCsv;

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CreditNote\Fetch")]
#[ORM\Table(name: "credit_note")]
class CreditNote implements EntityInterfaceExportCsv
{
    // Columns
    use EntityTraitId, EntityTraitReference, EntityTraitAmount, EntityTraitStatement, EntityTraitCreationDate;

    /**
     * @return string
     */
    static function csvGetHeadings(): string
    {
        return 'Reference,Creation date,Amount,Statement reference,Creditor name,Debtor name' . PHP_EOL;
    }

    /**
     * @return string
     */
    public function csvGetData(): string
    {
        $formattedCreationDate = $this->getCreationDate()->format('Y-m-d');

        return
            $this->getReference() . ',' .
            $formattedCreationDate . ',' .
            $this->getAmount() . ',' .
            $this->getStatement()->getReference() . ',' .
            $this->getStatement()->getCreditor()->getName() . ',' .
            $this->getStatement()->getDebtor()->getName() .
            PHP_EOL
        ;
    }
}