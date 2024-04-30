<?php

namespace App\Filter;

use App\Filter\Payment as FilterPayment;
use App\Filter\Invoice as FilterInvoice;
use App\Filter\CreditNote as FilterCreditNote;

class Statement
{
    public function __construct()
    {
        $this->setFilterInvoice(new FilterInvoice());
        $this->setFilterCreditNote(new FilterCreditNote());
        $this->setFilterPayment(new FilterPayment());
    }

    private $creationDateFrom;
    private $creationDateTo;

    /** @var FilterInvoice */
    private $filterInvoice;

    /** @var FilterCreditNote */
    private $filterCreditNote;

    /** @var FilterPayment */
    private $filterPayment;

    public function getCreationDateFrom()
    {
        return $this->creationDateFrom;
    }

    public function setCreationDateFrom($creationDateFrom): void
    {
        $this->creationDateFrom = $creationDateFrom;
    }

    public function getCreationDateTo()
    {
        return $this->creationDateTo;
    }

    public function setCreationDateTo($creationDateTo): void
    {
        $this->creationDateTo = $creationDateTo;
    }

    public function getFilterInvoice(): Invoice
    {
        return $this->filterInvoice;
    }

    public function setFilterInvoice(Invoice $filterInvoice): void
    {
        $this->filterInvoice = $filterInvoice;
    }

    public function getFilterCreditNote(): CreditNote
    {
        return $this->filterCreditNote;
    }

    public function setFilterCreditNote(CreditNote $filterCreditNote): void
    {
        $this->filterCreditNote = $filterCreditNote;
    }

    public function getFilterPayment(): Payment
    {
        return $this->filterPayment;
    }

    public function setFilterPayment(Payment $filterPayment): void
    {
        $this->filterPayment = $filterPayment;
    }
}