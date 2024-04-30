<?php

namespace App\Controller\Export;

use App\Controller\Fetch\Payment as ControllerFetchPayment;
use App\Entity\Payment as EntityPayment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Controller\Export\Trait\Csv as ControllerExportTraitCsv;

class Payment extends AbstractController
{
    use ControllerExportTraitCsv;

    /** @var ControllerFetchPayment  */
    private $controllerFetchPayment;

    public function __construct(ControllerFetchPayment $controllerFetchPayment)
    {
        $this->controllerFetchPayment = $controllerFetchPayment;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    #[Route("/datagird_payments_export_csv", name: "datagird-payments-export-csv")]
    public function exportToCsv(Request $request)
    {
        $csvData = $this->csvGetData($request);
        $fileName = $this->csvGetFileName('Payments');
        return $this->csvCreateStreamedResponse($csvData, $fileName);
    }

    /**
     * @param Request $request
     * @return EntityPayment[]
     */
    private function fetchData(Request $request):array
    {
        // Fetch data
        return $this->controllerFetchPayment->fetchPaymentsBySession($request->getSession());
    }

    /**
     * @return array
     */
    private function prepareHeaders():array
    {
        $exportData = [];
        $exportData['payment_reference'] = 'Payment reference';
        $exportData['payment_creation_date'] = 'Payment creation date';
        $exportData['payment_amount'] = 'Payment amount';
        $exportData['payment_iban'] = 'Payment IBAN';
        $exportData['statement_reference'] = 'Statement reference';
        $exportData['creditor_name'] = 'Creditor name';
        $exportData['debtor_name'] = 'Debtor name';
        return $exportData;
    }

    /**
     * @param EntityPayment[] $payments
     * @return array
     */
    private function prepareData(array $payments):array
    {
        $exportData = [];

        /** @var EntityPayment $payment */
        foreach ($payments as $payment) {
            $data = [];
            // Invoice data
            $data['payment_reference'] = $payment->getReference();
            $data['payment_creation_date'] = $payment->getCreationDate()->format('Y-m-d');
            $data['payment_amount'] = $payment->getAmount();
            $data['payment_iban'] =  $payment->getIban();

            // Statement data
            $data['statement_reference'] = $payment->getStatement()->getReference();

            // Creditor data
            $data['creditor_name'] = $payment->getStatement()->getCreditor()->getName();

            // Debtor data
            $data['debtor_name'] = $payment->getStatement()->getDebtor()->getName();

            $exportData[] = $data;
        }

        return $exportData;
    }
}