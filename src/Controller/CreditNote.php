<?php

namespace App\Controller;

// Controller
use App\Controller\Exporter\Csv as ControllerExporterCsv;

// Entity
use App\Entity\CreditNote as EntityCreditNote;

// Repository
use App\Repository\CreditNote\Fetch as RepositoryCreditNoteFetch;

// Filter
use App\Filter\CreditNote as FilterCreditNote;

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

class CreditNote  extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RepositoryCreditNoteFetch
     */
    private $RepositoryCompanyFetch;

    public function __construct(EntityManagerInterface $entityManager, RepositoryCreditNoteFetch $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->RepositoryCompanyFetch = $userRepository;
    }

    #[Route("/datagird_credit_note_export_csv", name: "datagird-credit-note-export-csv")]
    public function exportToCsv(Request $request)
    {
        // Fetch data
        $creditNotes = $this->fetchCreditNotesBySession($request->getSession());

        // Convert to csv data
        $csvData = EntityCreditNote::csvGetHeadings();
        foreach ($creditNotes as $creditNote) {
            $csvData .= $creditNote->csvGetData();
        }

        // Csv exporter
        $exporter = new ControllerExporterCsv();
        $fileName = $exporter->buildFileName('Credit notes');
        return $exporter->createExport($csvData, $fileName);
    }

    #[Route("/datagrid_credit_note_load", name: "datagrid-credit-note-load")]
    public function creditNoteGridOnLoad(Request $request)
    {
        $invoices = $this->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }

    #[Route('/datagird_credit_note_filter_apply', name: 'datagird-credit-note-filter-apply')]
    public function creditNoteGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->createFilter(new WrapperInputBag($request->query));
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }


    #[Route('/datagird_credit_note_filter_clear', name: 'datagird-credit-note-filter-clear')]
    public function creditNoteGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterCreditNote();
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchCreditNotesBySession($request->getSession());
        return $this->renderCreditNoteGrid($invoices);
    }

    /**
     * @param FilterCreditNote $filter
     * @return EntityCreditNote[]
     */
    private function fetchCreditNotesBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        return $this->entityManager->getRepository(EntityCreditNote::class)->searchByFilter($filter);
    }

    /**
     * @param $session
     * @param FilterCreditNote $filter
     * @return void
     */
    private function setSessionByFilter($session, FilterCreditNote $filter)
    {
        $session->set('filter_creditNote_creationDateFrom', $filter->getCreationDateFrom());
        $session->set('filter_creditNote_creationDateTo', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterCreditNote
     */
    private function createFilter(InterfaceGet $object)
    {
        $filter = new FilterCreditNote();
        $filter->setCreationDateFrom($object->get('filter_creditNote_creationDateFrom'));
        $filter->setCreationDateTo($object->get('filter_creditNote_creationDateTo'));

        return $filter;
    }

    /**
     * @param $creditNotes
     * @return Response
     */
    private function renderCreditNoteGrid($creditNotes): Response
    {
        return $this->render('datagrid_credit_notes.html.twig', [
            'creditNotes' => $creditNotes,
        ]);
    }
}
