<?php

namespace App\Controller;

// Controller
use App\Controller\Fetch\Invoice as ControllerFetchInvoice;

// Entity
use App\Entity\Invoice as EntityInvoice;

// Repository

// Filter
use App\Filter\Invoice as FilterInvoice;

// Interface

// Wrapper
use App\Wrapper\InputBag as WrapperInputBag;

// Vendor
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Invoice extends AbstractController
{
    /** @var ControllerFetchInvoice  */
    private $controllerFetchInvoice;

    public function __construct(ControllerFetchInvoice $controllerFetchInvoice)
    {
        $this->controllerFetchInvoice = $controllerFetchInvoice;
    }

    #[Route("/datagrid_invoices_load", name: "datagrid-invoices-load")]
    public function invoiceGridOnLoad(Request $request)
    {
        $invoices = $this->controllerFetchInvoice->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    #[Route('/datagird_invoices_filter_apply', name: 'datagird-invoices-filter-apply')]
    public function invoiceGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->controllerFetchInvoice->createFilter(new WrapperInputBag($request->query));
        $this->controllerFetchInvoice->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchInvoice->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    #[Route('/datagird_invoices_filter_clear', name: 'datagird-invoices-filter-clear')]
    public function invoiceGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterInvoice();
        $this->controllerFetchInvoice->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchInvoice->fetchInvoicesBySession($request->getSession());
        return $this->renderInvoiceGrid($invoices);
    }

    /**
     * @param EntityInvoice[] $invoices
     * @return Response
     */
    private function renderInvoiceGrid(array $invoices): Response
    {
        return $this->render('datagrid_invoices.html.twig', [
            'invoices' => $invoices,
        ]);
    }
}
