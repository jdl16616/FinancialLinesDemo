<?php

namespace App\Tests\Repository\CreditNote;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use App\Entity\CreditNote;
use App\Repository\CreditNote\Fetch;

class FetchTest extends TestCase
{
    public function testFindOneById(): void
    {
        // Create a mock of ManagerRegistry
        $registry = $this->createMock(ManagerRegistry::class);

        // Create a mock of EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $registry->method('getManagerForClass')->willReturn($entityManager);

        // Create a mock of QueryBuilder
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $entityManager->method('createQueryBuilder')->willReturn($queryBuilder);

        // Create a mock of AbstractQuery
        $query = $this->createMock(Query::class);
        $query->method('getOneOrNullResult')->willReturn(new CreditNote()); // Mock the result
        // Mock getQuery method of QueryBuilder
        $queryBuilder->method('getQuery')->willReturn($query);

        // Create an instance of the Fetch repository class
        $fetchRepository = new Fetch($registry);

        // Test the findOneById method
        $result = $fetchRepository->findOneById(1); // Provide an example ID to test
        var_dump($result);
//        $this->assertInstanceOf(CreditNote::class, $result); // Assert that the result is an instance of CreditNote
    }
}