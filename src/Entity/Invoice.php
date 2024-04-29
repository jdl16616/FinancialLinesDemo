<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Amount as EntityTraitAmount;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\Column\DueDate as EntityTraitDueDate;
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Creditor as EntityTraitCreditorId;
use App\Entity\Trait\ColumnAssociated\Debtor as EntityTraitDebtorId;

// Interface
use App\Entity\Interface\Export\Csv as EntityInterfaceExportCsv;

// Vendor
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: "App\Repository\Invoice\Fetch")]
#[ORM\Table(name: "Invoice")]
class Invoice implements EntityInterfaceExportCsv
{
    // Columns
    use EntityTraitId, EntityTraitReference, EntityTraitCreationDate, EntityTraitAmount, EntityTraitCreditorId, EntityTraitDebtorId, EntityTraitDueDate;

    /**
     * @return string
     */
    static function csvGetHeadings(): string
    {
        return 'Reference,Creation date,Due date,Amount,Creditor name,Debtor name' . PHP_EOL;
    }

    /**
     * @return string
     */
    public function csvGetData(): string
    {
        $formattedCreationDate = $this->getCreationDate()->format('Y-m-d');
        $formattedDueDate = $this->getDueDate()->format('Y-m-d');

        return sprintf(
            '%s,%s,%s,%s,%s,%s' . PHP_EOL,
            $this->getReference(),
            $formattedCreationDate,
            $formattedDueDate,
            $this->getAmount(),
            $this->getCreditor()->getName(),
            $this->getDebtor()->getName(),
        );
    }
}