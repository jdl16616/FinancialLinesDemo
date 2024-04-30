<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Amount as EntityTraitAmount;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\Column\DueDate as EntityTraitDueDate;
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Statement as EntityTraitStatement;

// Interface

// Vendor
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: "App\Repository\Invoice\Fetch")]
#[ORM\Table(name: "Invoice")]
class Invoice
{
    // Columns
    use EntityTraitId, EntityTraitReference, EntityTraitCreationDate, EntityTraitAmount, EntityTraitStatement, EntityTraitDueDate;
}