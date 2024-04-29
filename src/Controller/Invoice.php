<?php

namespace App\Controller;

// Controller
use App\Controller\Exporter\Csv as ControllerExporterCsv;

// Entity
use App\Entity\Invoice as EntityInvoice;

// Repository
use App\Repository\Invoice\Fetch as RepositoryInvoiceFetch;

// Filter
use App\Filter\Invoice as FilterInvoice;

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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Invoice  extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RepositoryInvoiceFetch
     */
    private $RepositoryCompanyFetch;

    public function __construct(EntityManagerInterface $entityManager, RepositoryInvoiceFetch $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->RepositoryCompanyFetch = $userRepository;
    }

    #[Route("/datagird_invoices_export_csv", name: "datagird-invoices-export-csv")]
    public function exportToCsv(Request $request)
    {
        // Fetch data
        $invoices = $this->fetchInvoicesBySession($request->getSession());

        // Convert to csv data
        $csvData = EntityInvoice::csvGetHeadings();
        foreach ($invoices as $invoice) {
            $csvData .= $invoice->csvGetData();
        }

        // Csv exporter
        $exporter = new ControllerExporterCsv();
        $fileName = $exporter->buildFileName('Invoices');
        return $exporter->createExport($csvData, $fileName);
    }

    #[Route("/datagrid_invoices_load", name: "datagrid-invoices-load")]
    public function invoiceGridOnLoad(Request $request)
    {
        $invoices = $this->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    #[Route('/datagird_invoices_filter_apply', name: 'datagird-invoices-filter-apply')]
    public function invoiceGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->createFilter(new WrapperInputBag($request->query));
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    #[Route('/datagird_invoices_filter_clear', name: 'datagird-invoices-filter-clear')]
    public function invoiceGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterInvoice();
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    /**
     * @param SessionInterface $session
     * @return EntityInvoice[]
     */
    private function fetchInvoicesBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        return $this->entityManager->getRepository(EntityInvoice::class)->searchByFilter($filter);
    }

    /**
     * @param $session
     * @param FilterInvoice $filter
     * @return void
     */
    private function setSessionByFilter($session, FilterInvoice $filter)
    {
        $session->set('filter_invoice_creationDateFrom', $filter->getCreationDateFrom());
        $session->set('filter_invoice_creationDateTo', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterInvoice
     */
    private function createFilter(InterfaceGet $object)
    {
        $filter = new FilterInvoice();
        $filter->setCreationDateFrom($object->get('filter_invoice_creationDateFrom'));
        $filter->setCreationDateTo($object->get('filter_invoice_creationDateTo'));

        return $filter;
    }

    /**
     * @param $invoices
     * @return Response
     */
    private function renderInvoiceGrid($invoices): Response
    {
        return $this->render('datagrid_invoices.html.twig', [
            'invoices' => $invoices,
        ]);
    }
}
