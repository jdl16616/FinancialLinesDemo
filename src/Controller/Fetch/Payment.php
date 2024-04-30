<?php

namespace App\Controller\Fetch;

use App\Entity\Payment as EntityPayment;
use App\Filter\Payment as FilterPayment;
use App\Interface\Get as InterfaceGet;
use App\Repository\Payment\Fetch as RepositoryPaymentFetch;
use App\Wrapper\Session as WrapperSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Payment
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RepositoryPaymentFetch
     */
    private $repositoryPaymentFetch;


    public function __construct(EntityManagerInterface $entityManager, RepositoryPaymentFetch $repositoryPaymentFetch)
    {
        $this->entityManager = $entityManager;
        $this->repositoryPaymentFetch = $repositoryPaymentFetch;
    }

    /**
     * @param SessionInterface $session
     * @return EntityPayment[]
     */
    public function fetchPaymentsBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        $payments = $this->repositoryPaymentFetch->searchByFilter($filter);

        return $payments ?? [];
    }

    /**
     * @param $session
     * @param FilterPayment $filter
     * @return void
     */
    public function setSessionByFilter($session, FilterPayment $filter)
    {
        $session->set('filter_payment_creationDate_from', $filter->getCreationDateFrom());
        $session->set('filter_payment_creationDate_to', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterPayment
     */
    public function createFilter(InterfaceGet $object):FilterPayment
    {
        $filter = new FilterPayment();
        $filter->setCreationDateFrom($object->get('filter_payment_creationDate_from'));
        $filter->setCreationDateTo($object->get('filter_payment_creationDate_to'));

        return $filter;
    }
}