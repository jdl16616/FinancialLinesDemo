<?php

namespace App\Controller;

// Entity
use App\Entity\Company as EntityCompany;

// Repository
use App\Repository\Company\Fetch as RepositoryCompanyFetch;

// Vendor
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Company  extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RepositoryCompanyFetch
     */
    private $RepositoryCompanyFetch;

    public function __construct(EntityManagerInterface $entityManager, RepositoryCompanyFetch $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->RepositoryCompanyFetch = $userRepository;
    }

    #[Route("/", name: "company_list")]
    public function index(): Response
    {
        $entities =  $this->entityManager->getRepository(EntityCompany::class)->findAll();

        return $this->render('datagrid_companies.html.twig', [
            'companies' => $entities,
        ]);
    }
}
