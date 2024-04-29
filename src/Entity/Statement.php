<?php

namespace App\Entity;


// Entity traits
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\ColumnAssociated\Creditor as EntityTraitCreditorId;
use App\Entity\Trait\ColumnAssociated\Debtor as EntityTraitDebtorId;

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Statement\Fetch")]
#[ORM\Table(name: "Statement")]
class Statement
{
    use EntityTraitId,EntityTraitReference,EntityTraitCreditorId,EntityTraitDebtorId;
}