<?php

namespace App\Controller;

// Controller
use App\Controller\Fetch\CreditNote as ControllerFetchCreditNote;

// Entity
use App\Entity\CreditNote as EntityCreditNote;

// Repository

// Filter
use App\Filter\CreditNote as FilterCreditNote;

// Interface

// Wrapper
use App\Wrapper\InputBag as WrapperInputBag;

// Vendor
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreditNote extends AbstractController
{
    /**
     * @var ControllerFetchCreditNote
     */
    private $controllerFetchCreditNote;

    public function __construct(ControllerFetchCreditNote $controllerFetchCreditNote)
    {
        $this->controllerFetchCreditNote = $controllerFetchCreditNote;
    }

    #[Route("/datagrid_credit_note_load", name: "datagrid-credit-note-load")]
    public function creditNoteGridOnLoad(Request $request)
    {
        $invoices = $this->controllerFetchCreditNote->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }

    #[Route('/datagird_credit_note_filter_apply', name: 'datagird-credit-note-filter-apply')]
    public function creditNoteGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->controllerFetchCreditNote->createFilter(new WrapperInputBag($request->query));
        $this->controllerFetchCreditNote->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchCreditNote->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }


    #[Route('/datagird_credit_note_filter_clear', name: 'datagird-credit-note-filter-clear')]
    public function creditNoteGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterCreditNote();
        $this->controllerFetchCreditNote->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchCreditNote->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }

    /**
     * @param EntityCreditNote[] $creditNotes
     * @return Response
     */
    private function renderCreditNoteGrid(array $creditNotes): Response
    {
        return $this->render('datagrid_credit_notes.html.twig', [
            'creditNotes' => $creditNotes,
        ]);
    }
}
