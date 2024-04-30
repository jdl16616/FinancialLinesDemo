<?php

namespace App\Controller;

// Entity
use App\Entity\Company as EntityCompany;

// Repository

// Vendor
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Company extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
