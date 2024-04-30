<?php

namespace App\Entity;

// Entity
use App\Entity\Invoice as EntityInvoice;
use App\Entity\CreditNote as EntityCreditNote;
use App\Entity\Payment as EntityPayment;

// Entity traits
use App\Entity\Trait\Column\Id as EntityTraitId;
use App\Entity\Trait\Column\Reference as EntityTraitReference;
use App\Entity\Trait\Column\CreationDate as EntityTraitCreationDate;
use App\Entity\Trait\ColumnAssociated\Creditor as EntityTraitCreditorId;
use App\Entity\Trait\ColumnAssociated\Debtor as EntityTraitDebtorId;

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Statement\Fetch")]
#[ORM\Table(name: "Statement")]
class Statement
{
    use EntityTraitId,EntityTraitReference,EntityTraitCreditorId,EntityTraitDebtorId,EntityTraitCreationDate;

    /**
     * @var EntityInvoice[]|null
     */
    #[ORM\OneToMany(targetEntity: EntityInvoice::class, mappedBy: "statement")]
    private $invoices;

    /**
     * @var EntityCreditNote[]|null
     */
    #[ORM\OneToMany(targetEntity: EntityCreditNote::class, mappedBy: "statement")]
    private $creditNotes;

    /**
     * @var EntityPayment[]|null
     */
    #[ORM\OneToMany(targetEntity: EntityPayment::class, mappedBy: "statement")]
    private $payments;

    /**
     * @return Invoice[]|null
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    public function setInvoices(?array $invoices): void
    {
        $this->invoices = $invoices;
    }

    /**
     * @return CreditNote[]|null
     */
    public function getCreditNotes()
    {
        return $this->creditNotes;
    }

    public function setCreditNotes(?array $creditNotes): void
    {
        $this->creditNotes = $creditNotes;
    }

    /**
     * @return Payment[]|null
     */
    public function getPayments()
    {
        return $this->payments;
    }

    public function setPayments(?array $payments): void
    {
        $this->payments = $payments;
    }
}