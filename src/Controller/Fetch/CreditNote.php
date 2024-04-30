<?php

namespace App\Controller\Fetch;

use App\Entity\CreditNote as EntityCreditNote;
use App\Filter\CreditNote as FilterCreditNote;
use App\Interface\Get as InterfaceGet;
use App\Repository\CreditNote\Fetch as RepositoryCreditNoteFetch;
use App\Wrapper\Session as WrapperSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreditNote
{
    /**
     * @var RepositoryCreditNoteFetch
     */
    private $repositoryCreditNoteFetch;

    public function __construct(RepositoryCreditNoteFetch $repositoryCreditNoteFetch)
    {
        $this->repositoryCreditNoteFetch = $repositoryCreditNoteFetch;
    }

    /**
     * @param SessionInterface $session
     * @return EntityCreditNote[]
     */
    public function fetchCreditNotesBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        $creditNotes = $this->repositoryCreditNoteFetch->searchByFilter($filter);

        return $creditNotes ?? [];
    }

    /**
     * @param $session
     * @param FilterCreditNote $filter
     * @return void
     */
    public function setSessionByFilter($session, FilterCreditNote $filter)
    {
        $session->set('filter_creditNote_creationDate_from', $filter->getCreationDateFrom());
        $session->set('filter_creditNote_creationDate_to', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterCreditNote
     */
    public function createFilter(InterfaceGet $object):FilterCreditNote
    {
        $filter = new FilterCreditNote();
        $filter->setCreationDateFrom($object->get('filter_creditNote_creationDate_from'));
        $filter->setCreationDateTo($object->get('filter_creditNote_creationDate_to'));

        return $filter;
    }
}