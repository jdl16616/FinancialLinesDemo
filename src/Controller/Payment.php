<?php

namespace App\Controller;

// Controller
use App\Controller\Fetch\Payment as ControllerFetchPayment;

// Entity
use App\Entity\Payment as EntityPayment;

// Repository

// Filter
use App\Filter\Payment as FilterPayment;

// Interface

// Wrapper
use App\Wrapper\InputBag as WrapperInputBag;

// Vendor
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Payment extends AbstractController
{
    /** @var ControllerFetchPayment  */
    private $controllerFetchPayment;


    public function __construct(ControllerFetchPayment $controllerFetchPayment)
    {
        $this->controllerFetchPayment = $controllerFetchPayment;
    }

    #[Route("/datagrid_payments_load", name: "datagrid-payments-load")]
    public function paymentGridOnLoad(Request $request)
    {
        $invoices = $this->controllerFetchPayment->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    #[Route('/datagird_payments_filter_apply', name: 'datagird-payments-filter-apply')]
    public function paymentGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->controllerFetchPayment->createFilter(new WrapperInputBag($request->query));
        $this->controllerFetchPayment->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchPayment->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    #[Route('/datagird_payments_filter_clear', name: 'datagird-payments-filter-clear')]
    public function paymentGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterPayment();
        $this->controllerFetchPayment->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->controllerFetchPayment->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    /**
     * @param EntityPayment[] $payments
     * @return Response
     */
    private function renderPaymentGrid(array $payments): Response
    {
        return $this->render('datagrid_payments.html.twig', [
            'payments' => $payments,
        ]);
    }
}
