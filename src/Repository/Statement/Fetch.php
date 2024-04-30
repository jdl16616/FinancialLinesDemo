<?php

namespace App\Repository\Statement;

// Entity
use App\Entity\Statement as EntityStatement;
use App\Entity\Invoice as EntityInvoice;
use App\Entity\CreditNote as EntityCreditNote;
use App\Entity\Payment as EntityPayment;

// Filter
use App\Filter\Statement as FilterStatement;

// Vendor
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Fetch extends ServiceEntityRepository
{
    const ALIAS_STATEMENT = 's';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityStatement::class);
    }

    /**
     * @param FilterStatement $filter
     * @return EntityStatement[]
     */
    public function searchByFilter(FilterStatement $filter)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_STATEMENT);
        $queryBuilder->select('s, i, cn, p');
        $queryBuilder->leftJoin('s.invoices', 'i');
        $queryBuilder->leftJoin('s.creditNotes', 'cn');
        $queryBuilder->leftJoin('s.payments', 'p');

        // Statement
        if ($filter->getCreationDateFrom())
            $queryBuilder->andWhere(self::ALIAS_STATEMENT . '.creationDate >= :creationDateFrom')->setParameter('creationDateFrom', $filter->getCreationDateFrom());
        if ($filter->getCreationDateTo())
            $queryBuilder->andWhere(self::ALIAS_STATEMENT . '.creationDate <= :creationDateTo')->setParameter('creationDateTo', $filter->getCreationDateTo());
        // Invoice
        if ($filter->getFilterInvoice()->getCreationDateFrom())
            $queryBuilder->andWhere('i.creationDate >= :creationDateFrom')->setParameter('creationDateFrom', $filter->getFilterInvoice()->getCreationDateFrom());
        if ($filter->getFilterInvoice()->getCreationDateTo())
            $queryBuilder->andWhere('i.creationDate <= :creationDateTo')->setParameter('creationDateTo', $filter->getFilterInvoice()->getCreationDateTo());
        // Credit note
        if ($filter->getFilterCreditNote()->getCreationDateFrom())
            $queryBuilder->andWhere('cn.creationDate >= :creationDateFrom')->setParameter('creationDateFrom', $filter->getFilterCreditNote()->getCreationDateFrom());
        if ($filter->getFilterCreditNote()->getCreationDateTo())
            $queryBuilder->andWhere('cn.creationDate <= :creationDateTo')->setParameter('creationDateTo', $filter->getFilterCreditNote()->getCreationDateTo());
        // Payment
        if ($filter->getFilterPayment()->getCreationDateFrom())
            $queryBuilder->andWhere('p.creationDate >= :creationDateFrom')->setParameter('creationDateFrom', $filter->getFilterPayment()->getCreationDateFrom());
        if ($filter->getFilterPayment()->getCreationDateTo())
            $queryBuilder->andWhere('p.creationDate <= :creationDateTo')->setParameter('creationDateTo', $filter->getFilterPayment()->getCreationDateTo());

        return $queryBuilder->getQuery()->getResult();
    }
}