<?php

namespace App\Controller\Fetch;

use App\Entity\Invoice as EntityInvoice;
use App\Filter\Invoice as FilterInvoice;
use App\Interface\Get as InterfaceGet;
use App\Repository\Invoice\Fetch as RepositoryInvoiceFetch;
use App\Wrapper\Session as WrapperSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Invoice
{
    /**
     * @var RepositoryInvoiceFetch
     */
    private $repositoryInvoiceFetch;


    public function __construct(RepositoryInvoiceFetch $repositoryInvoiceFetch)
    {
        $this->repositoryInvoiceFetch = $repositoryInvoiceFetch;
    }

    /**
     * @param SessionInterface $session
     * @return EntityInvoice[]
     */
    public function fetchInvoicesBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        $invoices = $this->repositoryInvoiceFetch->searchByFilter($filter);

        return $invoices ?? [];
    }

    /**
     * @param SessionInterface $session
     * @param FilterInvoice $filter
     * @return void
     */
    public function setSessionByFilter(SessionInterface $session, FilterInvoice $filter)
    {
        $session->set('filter_invoice_creationDate_from', $filter->getCreationDateFrom());
        $session->set('filter_invoice_creationDate_to', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterInvoice
     */
    public function createFilter(InterfaceGet $object):FilterInvoice
    {
        $filter = new FilterInvoice();
        $filter->setCreationDateFrom($object->get('filter_invoice_creationDate_from'));
        $filter->setCreationDateTo($object->get('filter_invoice_creationDate_to'));

        return $filter;
    }
}