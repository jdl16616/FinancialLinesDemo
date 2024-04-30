<?php

namespace App\Controller\Export;

use App\Controller\Fetch\Invoice as ControllerFetchInvoice;
use App\Entity\Invoice as EntityInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Controller\Export\Trait\Csv as ControllerExportTraitCsv;

class Invoice extends AbstractController
{
    use ControllerExportTraitCsv;

    /** @var ControllerFetchInvoice  */
    private $controllerFetchInvoice;

    public function __construct(ControllerFetchInvoice $controllerFetchInvoice)
    {
        $this->controllerFetchInvoice = $controllerFetchInvoice;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    #[Route("/datagird_invoices_export_csv", name: "datagird-invoices-export-csv")]
    public function exportToCsv(Request $request)
    {
        $csvData = $this->csvGetData($request);
        $fileName = $this->csvGetFileName('Invoices');
        return $this->csvCreateStreamedResponse($csvData, $fileName);
    }

    /**
     * @param Request $request
     * @return EntityInvoice[]
     */
    private function fetchData(Request $request)
    {
        // Fetch data
        return $this->controllerFetchInvoice->fetchInvoicesBySession($request->getSession());
    }

    /**
     * @return array
     */
    private function prepareHeaders():array
    {
        $exportData = [];
        $exportData['invoice_reference'] = 'Invoice reference';
        $exportData['invoice_creation_date'] = 'Invoice creation date';
        $exportData['invoice_due_date'] = 'Invoice due date';
        $exportData['invoice_amount'] = 'Invoice amount';
        $exportData['statement_reference'] = 'Statement reference';
        $exportData['creditor_name'] = 'Creditor name';
        $exportData['debtor_name'] = 'Debtor name';
        return $exportData;
    }

    /**
     * @param EntityInvoice[] $invoices
     * @return array
     */
    private function prepareData(array $invoices):array
    {
        $exportData = [];

        /** @var EntityInvoice $invoice */
        foreach ($invoices as $invoice) {
            $data = [];
            // Invoice data
            $data['invoice_reference'] = $invoice->getReference();
            $data['invoice_creation_date'] = $invoice->getCreationDate()->format('Y-m-d');
            $data['invoice_due_date'] =  $invoice->getDueDate()->format('Y-m-d');
            $data['invoice_amount'] = $invoice->getAmount();

            // Statement data
            $data['statement_reference'] = $invoice->getStatement()->getReference();

            // Creditor data
            $data['creditor_name'] = $invoice->getStatement()->getCreditor()->getName();

            // Debtor data
            $data['debtor_name'] = $invoice->getStatement()->getDebtor()->getName();

            $exportData[] = $data;
        }

        return $exportData;
    }
}