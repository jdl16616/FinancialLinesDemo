<?php

namespace App\Tests\Repository\CreditNote;

use App\Controller\Company;
use App\Entity\Company as CompanyEntity;
use App\Repository\Company\Fetch as RepositoryCompanyFetch;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;


class CompanyTest extends TestCase
{
    public function testFindOneById(): void
    {
        // Create a mock of ManagerRegistry
        $registry = $this->createMock(ManagerRegistry::class);

        // Create a mock of EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $registry->method('getManagerForClass')->willReturn($entityManager);


        $repo = $this->createMock(RepositoryCompanyFetch::class);

        // Create an instance of the Fetch repository class
        $fetchRepository = new Company($entityManager, $repo);

        // Test the findOneById method
        $result = $fetchRepository->findOneById(1); // Provide an example ID to test
        $this->assertInstanceOf(CompanyEntity::class, $result); // Assert that the result is an instance of CreditNote
    }
}