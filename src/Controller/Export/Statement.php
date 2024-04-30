<?php

namespace App\Controller\Export;

use App\Controller\Fetch\Statement as ControllerFetchStatement;
use App\Entity\Statement as EntityStatement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Controller\Export\Trait\Csv as ControllerExportTraitCsv;

class Statement extends AbstractController
{
    use ControllerExportTraitCsv;

    /** @var ControllerFetchStatement  */
    private $controllerFetchStatement;

    public function __construct(ControllerFetchStatement $controllerFetchStatement)
    {
        $this->controllerFetchStatement = $controllerFetchStatement;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    #[Route("/datagird_statements_export_csv", name: "datagird-statements-export-csv")]
    public function exportToCsv(Request $request)
    {
        $csvData = $this->csvGetData($request);
        $fileName = $this->csvGetFileName('Statements');
        return $this->csvCreateStreamedResponse($csvData, $fileName);
    }

    /**
     * @param Request $request
     * @return EntityStatement[]
     */
    private function fetchData(Request $request):array
    {
        // Fetch data
        return $this->controllerFetchStatement->fetchStatementsBySession($request->getSession());
    }

    private function orderedColumns()
    {
        $columnNames = [];
        $columnNames['statement_reference'] = null;
        $columnNames['statement_creation_date'] = null;
        $columnNames['creditor_name'] = null;
        $columnNames['debtor_name'] = null;
        $columnNames['invoice_reference'] = null;
        $columnNames['invoice_creation_date'] = null;
        $columnNames['invoice_due_date'] = null;
        $columnNames['invoice_amount'] = null;
        $columnNames['credit_note_reference'] = null;
        $columnNames['credit_note_creation_date'] = null;
        $columnNames['credit_note_amount'] = null;
        $columnNames['payment_reference'] = null;
        $columnNames['payment_creation_date'] = null;
        $columnNames['payment_amount'] = null;
        $columnNames['payment_iban'] = null;
        return $columnNames;
    }

    /**
     * @return array
     */
    private function prepareHeaders():array
    {
        $exportData = $this->orderedColumns();
        $exportData['statement_reference'] = 'Statement reference';
        $exportData['statement_creation_date'] = 'Statement creation date';
        $exportData['creditor_name'] = 'Creditor name';
        $exportData['debtor_name'] = 'Debtor name';
        $exportData['invoice_reference'] = 'Invoice reference';
        $exportData['invoice_creation_date'] = 'Invoice creation date';
        $exportData['invoice_due_date'] = 'Invoice due date';
        $exportData['invoice_amount'] = 'Invoice amount';
        $exportData['credit_note_reference'] = 'Credit note reference';
        $exportData['credit_note_creation_date'] = 'Credit note creation date';
        $exportData['credit_note_amount'] = 'Credit note amount';
        $exportData['payment_reference'] = 'Payment reference';
        $exportData['payment_creation_date'] = 'Payment creation date';
        $exportData['payment_amount'] = 'Payment amount';
        $exportData['payment_iban'] = 'Payment IBAN';
        return $exportData;
    }

    /**
     * @param EntityStatement[] $statements
     * @return array
     */
    private function prepareData(array $statements):array
    {
        $exportData = [];

        /** @var EntityStatement $statement */
        foreach ($statements as $statement) {
            $statementData = $this->getStatementData($statement);
            $invoiceDataArray = $this->getInvoiceData($statement);
            $creditNoteDataArray = $this->getCreditNoteData($statement);
            $paymentDataArray = $this->getPaymentData($statement);

            foreach($invoiceDataArray as $invoiceData)
            {
                $exportData[] = array_merge($this->orderedColumns(), $statementData, $invoiceData);
            }
            foreach($creditNoteDataArray as $creditNoteData)
            {
                $exportData[] = array_merge($this->orderedColumns(), $statementData, $creditNoteData);
            }
            foreach($paymentDataArray as $paymentData)
            {
                $exportData[] = array_merge($this->orderedColumns(), $statementData, $paymentData);
            }

            if (empty($exportData)) $exportData[] = $statementData;
        }

        return $exportData;
    }

    private function getStatementData(EntityStatement $statement):array
    {
        $data = [];

        // Invoice data
        $data['statement_reference'] = $statement->getReference();
        $data['statement_creation_date'] = $statement->getCreationDate()->format('Y-m-d');

        // Creditor data
        $data['creditor_name'] = $statement->getCreditor()->getName();

        // Debtor data
        $data['debtor_name'] = $statement->getDebtor()->getName();

        return $data;
    }

    /**
     * @param EntityStatement $statement
     * @return array
     */
    private function getInvoiceData(EntityStatement $statement):array
    {
        $exportData = [];

        foreach($statement->getInvoices() as $invoice)
        {
            $data = [];

            $data['invoice_reference'] = $invoice->getReference();
            $data['invoice_creation_date'] = $invoice->getCreationDate()->format('Y-m-d');
            $data['invoice_due_date'] = $invoice->getDueDate()->format('Y-m-d');
            $data['invoice_amount'] = $invoice->getAmount();

            $exportData[] = $data;
        }

        return $exportData;
    }

    /**
     * @param EntityStatement $statement
     * @return array
     */
    private function getCreditNoteData(EntityStatement $statement):array
    {
        $exportData = [];

        foreach($statement->getCreditNotes() as $creditNote)
        {
            $data = [];

            $data['credit_note_reference'] = $creditNote->getReference();
            $data['credit_note_creation_date'] = $creditNote->getCreationDate()->format('Y-m-d');
            $data['credit_note_amount'] = $creditNote->getAmount();

            $exportData[] = $data;
        }

        return $exportData;
    }

    /**
     * @param EntityStatement $statement
     * @return array
     */
    private function getPaymentData(EntityStatement $statement):array
    {
        $exportData = [];

        foreach($statement->getPayments() as $payment)
        {
            $data = [];

            $data['payment_reference'] = $payment->getReference();
            $data['payment_creation_date'] = $payment->getCreationDate()->format('Y-m-d');
            $data['payment_amount'] = $payment->getAmount();
            $data['payment_iban'] = $payment->getIban();

            $exportData[] = $data;
        }

        return $exportData;
    }
}