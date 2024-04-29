<?php

namespace App\Repository\CreditNote;

// Entity
use App\Entity\CreditNote as EntityCreditNote;

// Filter
use App\Filter\CreditNote as FilterCreditNote;

// Vendor
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Fetch extends ServiceEntityRepository
{
    const ALIAS_CREDITNOTE = 'cn';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityCreditNote::class);
    }

    public function findAllWithCompanies()
    {
        return $this->createQueryBuilder(self::ALIAS_CREDITNOTE)
            ->leftJoin(self::ALIAS_CREDITNOTE . '.creditor', 'c1')
            ->addSelect('c1')
            ->leftJoin(self::ALIAS_CREDITNOTE . '.debtor', 'c2')
            ->addSelect('c2')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param FilterCreditNote $filter
     * @return EntityCreditNote[]
     */
    public function searchByFilter(FilterCreditNote $filter)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS_CREDITNOTE);

        if ($filter->getCreationDateFrom()) {
            $queryBuilder->andWhere(self::ALIAS_CREDITNOTE . '.creationDate >= :creationDateFrom')
                ->setParameter('creationDateFrom', $filter->getCreationDateFrom());
        }
        if ($filter->getCreationDateTo()) {
            $queryBuilder->andWhere(self::ALIAS_CREDITNOTE . '.creationDate <= :creationDateTo')
                ->setParameter('creationDateTo', $filter->getCreationDateTo());
        }

        return $queryBuilder->getQuery()->getResult();
    }
}

