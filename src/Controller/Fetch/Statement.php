<?php

namespace App\Controller\Fetch;

use App\Entity\Statement as EntityStatement;
use App\Filter\Statement as FilterStatement;
use App\Interface\Get as InterfaceGet;
use App\Repository\Statement\Fetch as RepositoryStatementFetch;
use App\Wrapper\Session as WrapperSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Statement
{
    /**
     * @var RepositoryStatementFetch
     */
    private $repositoryStatementFetch;

    public function __construct(RepositoryStatementFetch $repositoryStatementFetch)
    {
        $this->repositoryStatementFetch = $repositoryStatementFetch;
    }

    /**
     * @param SessionInterface $session
     * @return EntityStatement[]
     */
    public function fetchStatementsBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        $statements = $this->repositoryStatementFetch->searchByFilter($filter);

        return $statements ?? [];
    }

    /**
     * @param $session
     * @param FilterStatement $filter
     * @return void
     */
    public function setSessionByFilter($session, FilterStatement $filter)
    {
        $session->set('filter_statement_creationDate_from', $filter->getCreationDateFrom());
        $session->set('filter_statement_creationDate_to', $filter->getCreationDateTo());

        $session->set('filter_statement_invoice_creationDate_from', $filter->getFilterInvoice()->getCreationDateFrom());
        $session->set('filter_statement_invoice_creationDate_to', $filter->getFilterInvoice()->getCreationDateTo());

        $session->set('filter_statement_creditNote_creationDate_from', $filter->getFilterCreditNote()->getCreationDateFrom());
        $session->set('filter_statement_creditNote_creationDate_to', $filter->getFilterCreditNote()->getCreationDateTo());

        $session->set('filter_statement_payment_creationDate_from', $filter->getFilterPayment()->getCreationDateFrom());
        $session->set('filter_statement_payment_creationDate_to', $filter->getFilterPayment()->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterStatement
     */
    public function createFilter(InterfaceGet $object)
    {
        $filter = new FilterStatement();
        $filter->setCreationDateFrom($object->get('filter_statement_creationDate_from'));
        $filter->setCreationDateTo($object->get('filter_statement_creationDate_to'));

        $filter->getFilterInvoice()->setCreationDateFrom($object->get('filter_statement_invoice_creationDate_from'));
        $filter->getFilterInvoice()->setCreationDateTo($object->get('filter_statement_invoice_creationDate_to'));

        $filter->getFilterCreditNote()->setCreationDateFrom($object->get('filter_statement_creditNote_creationDate_from'));
        $filter->getFilterCreditNote()->setCreationDateTo($object->get('filter_statement_creditNote_creationDate_to'));

        $filter->getFilterPayment()->setCreationDateFrom($object->get('filter_statement_payment_creationDate_from'));
        $filter->getFilterPayment()->setCreationDateTo($object->get('filter_statement_payment_creationDate_to'));

        return $filter;
    }
}