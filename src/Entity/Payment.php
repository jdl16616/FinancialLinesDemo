<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Amount as EntityTraitAmount;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\Column\Iban as EntityTraitIban;
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Statement as EntityTraitStatement;

// Interface

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Payment\Fetch")]
#[ORM\Table(name: "Payment")]
class Payment
{
    // Columns
    use EntityTraitId, EntityTraitReference, EntityTraitCreationDate, EntityTraitAmount, EntityTraitStatement, EntityTraitIban;
}