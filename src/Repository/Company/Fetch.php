<?php

namespace App\Repository\Company;

// Entity
use App\Entity\Company as EntityCompany;

// Vendor
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Fetch extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityCompany::class);
    }
}

