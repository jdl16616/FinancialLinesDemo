<?php

namespace App\Repository\Payment;

// Entity
use App\Entity\Payment as EntityPayment;


// Filter
use App\Filter\Payment as FilterPayment;

// Vendor
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Fetch extends ServiceEntityRepository
{
    const ALIAS_PAYMENT = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityPayment::class);
    }

    public function findAllWithCompanies()
    {
        return $this->createQueryBuilder(self::ALIAS_PAYMENT)
            ->leftJoin('p.creditor', 'c1')
            ->addSelect('c1')
            ->leftJoin('p.debtor', 'c2')
            ->addSelect('c2')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param FilterPayment $filter
     * @return EntityPayment[]
     */
    public function searchByFilter(FilterPayment $filter)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_PAYMENT);

        if ($filter->getCreationDateFrom()) {
            $queryBuilder->andWhere(self::ALIAS_PAYMENT . '.creationDate >= :creationDateFrom')
                ->setParameter('creationDateFrom', $filter->getCreationDateFrom());
        }
        if ($filter->getCreationDateTo()) {
            $queryBuilder->andWhere(self::ALIAS_PAYMENT . '.creationDate <= :creationDateTo')
                ->setParameter('creationDateTo', $filter->getCreationDateTo());
        }

        return $queryBuilder->getQuery()->getResult();
    }
}

