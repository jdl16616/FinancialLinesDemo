<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Amount as EntityTraitAmount;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Statement as EntityTraitStatement;

// Interface

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CreditNote\Fetch")]
#[ORM\Table(name: "credit_note")]
class CreditNote
{
    // Columns
    use EntityTraitId, EntityTraitReference, EntityTraitAmount, EntityTraitStatement, EntityTraitCreationDate;
}