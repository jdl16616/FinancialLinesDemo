<?php

namespace App\Controller;

// Controller
use App\Controller\Exporter\Csv as ControllerExporterCsv;

// Entity
use App\Entity\Payment as EntityPayment;

// Repository
use App\Repository\Payment\Fetch as RepositoryPaymentFetch;

// Filter
use App\Filter\Payment as FilterPayment;

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


class Payment  extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RepositoryPaymentFetch
     */
    private $RepositoryCompanyFetch;

    public function __construct(EntityManagerInterface $entityManager, RepositoryPaymentFetch $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->RepositoryCompanyFetch = $userRepository;
    }

    #[Route("/datagird_payments_export_csv", name: "datagird-payments-export-csv")]
    public function exportToCsv(Request $request)
    {
        // Fetch data
        $payments = $this->fetchPaymentsBySession($request->getSession());

        // Convert to csv data
        $csvData = EntityPayment::csvGetHeadings();
        foreach ($payments as $payment) {
            $csvData .= $payment->csvGetData();
        }

        // Csv exporter
        $exporter = new ControllerExporterCsv();
        $fileName = $exporter->buildFileName('Payments');
        return $exporter->createExport($csvData, $fileName);
    }

    #[Route("/datagrid_payments_load", name: "datagrid-payments-load")]
    public function paymentGridOnLoad(Request $request)
    {
        $invoices = $this->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    #[Route('/datagird_payments_filter_apply', name: 'datagird-payments-filter-apply')]
    public function paymentGridOnFilterApply(Request $request)
    {
        // Store inputs in session, as to maintain the current filters when traversing tabs
        $filter = $this->createFilter(new WrapperInputBag($request->query));
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    #[Route('/datagird_payments_filter_clear', name: 'datagird-payments-filter-clear')]
    public function paymentGridOnFilterReset(Request $request)
    {
        // Using an empty filter, we empty the session properties
        $filter = new FilterPayment();
        $this->setSessionByFilter($request->getSession(), $filter);

        $invoices = $this->fetchPaymentsBySession($request->getSession());
        return $this->renderPaymentGrid($invoices);
    }

    /**
     * @param SessionInterface $session
     * @return EntityPayment[]
     */
    private function fetchPaymentsBySession(SessionInterface $session):array
    {
        $filter = $this->createFilter(new WrapperSession($session));
        return $this->entityManager->getRepository(EntityPayment::class)->searchByFilter($filter);
    }

    /**
     * @param $session
     * @param FilterPayment $filter
     * @return void
     */
    private function setSessionByFilter($session, FilterPayment $filter)
    {
        $session->set('filter_payment_creationDateFrom', $filter->getCreationDateFrom());
        $session->set('filter_payment_creationDateTo', $filter->getCreationDateTo());
    }

    /**
     * @param InterfaceGet $object
     * @return FilterPayment
     */
    private function createFilter(InterfaceGet $object)
    {
        $filter = new FilterPayment();
        $filter->setCreationDateFrom($object->get('filter_payment_creationDateFrom'));
        $filter->setCreationDateTo($object->get('filter_payment_creationDateTo'));

        return $filter;
    }

    /**
     * @param $payments
     * @return Response
     */
    private function renderPaymentGrid($payments): Response
    {
        return $this->render('datagrid_payments.html.twig', [
            'payments' => $payments,
        ]);
    }
}
