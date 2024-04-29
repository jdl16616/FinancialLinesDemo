<?php

namespace App\Repository\Invoice;

// Entity
use App\Entity\Invoice as EntityInvoice;

// Filter
use App\Filter\Invoice as FilterInvoice;

// Vendor
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Fetch extends ServiceEntityRepository
{
    const ALIAS_INVOICE = 'i';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityInvoice::class);
    }

    public function findAllWithCompanies()
    {
        return $this->createQueryBuilder(self::ALIAS_INVOICE)
            ->leftJoin(self::ALIAS_INVOICE . '.creditor', 'c1')
            ->addSelect('c1')
            ->leftJoin(self::ALIAS_INVOICE . '.debtor', 'c2')
            ->addSelect('c2')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param FilterInvoice $filter
     * @return EntityInvoice[]
     */
    public function searchByFilter(FilterInvoice $filter)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_INVOICE);

        if ($filter->getCreationDateFrom()) {
            $queryBuilder->andWhere(self::ALIAS_INVOICE . '.creationDate >= :creationDateFrom')
                ->setParameter('creationDateFrom', $filter->getCreationDateFrom());
        }
        if ($filter->getCreationDateTo()) {
            $queryBuilder->andWhere(self::ALIAS_INVOICE . '.creationDate <= :creationDateTo')
                ->setParameter('creationDateTo', $filter->getCreationDateTo());
        }

        return $queryBuilder->getQuery()->getResult();
    }
}

