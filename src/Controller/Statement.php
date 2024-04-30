<?php

namespace App\Controller;

// Controller
use App\Controller\Fetch\Statement as ControllerFetchStatement;

// Entity
use App\Entity\Statement as EntityStatement;

// Filter
use App\Filter\Statement as FilterStatement;

// Repository
use App\Repository\Statement\Fetch as RepositoryStatementFetch;

// Interface
use App\Interface\Get as InterfaceGet;

// Wrapper
use App\Wrapper\InputBag as WrapperInputBag;
use App\Wrapper\Session as WrapperSession;

// Vendor
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Statement extends AbstractController
{
    /** @var ControllerFetchStatement  */
    private $controllerFetchStatement;

    public function __construct(ControllerFetchStatement $controllerFetchStatement)
    {
        $this->controllerFetchStatement = $controllerFetchStatement;
    }

    #[Route("/datagrid_statements_load", name: "datagrid-statements-load")]
    public function statementGridOnLoad(Request $request)
    {
        $invoices = $this->controllerFetchStatement->fetchStatementsBySession($request->getSession());
        return $this->renderStatementGrid($invoices);
    }

    #[Route('/datagird_statements_filter_apply', name: 'datagird-statement-filter-apply')]
    public function statementGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->controllerFetchStatement->createFilter(new WrapperInputBag($request->query));
        $this->controllerFetchStatement->setSessionByFilter($request->getSession(), $filter);

        $statements = $this->controllerFetchStatement->fetchStatementsBySession($request->getSession());
        return $this->renderStatementGrid($statements);
    }

    #[Route('/datagird_statements_filter_clear', name: 'datagird-statement-filter-clear')]
    public function creditNoteGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterStatement();
        $this->controllerFetchStatement->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchStatement->fetchStatementsBySession($request->getSession());
        return $this->renderStatementGrid($invoices);
    }

    /**
     * @param EntityStatement[] $statements
     * @return Response
     */
    private function renderStatementGrid(array $statements): Response
    {
        return $this->render('datagrid_statements.html.twig', [
            'statements' => $statements,
        ]);
    }
}