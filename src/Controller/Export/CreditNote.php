<?php

namespace App\Controller\Export;

use App\Controller\Fetch\CreditNote as ControllerFetchCreditNote;
use App\Entity\CreditNote as EntityCreditNote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Controller\Export\Trait\Csv as ControllerExportTraitCsv;

class CreditNote extends AbstractController
{
    use ControllerExportTraitCsv;

    /** @var ControllerFetchCreditNote  */
    private $controllerFetchCreditNote;

    public function __construct(ControllerFetchCreditNote $controllerFetchCreditNote)
    {
        $this->controllerFetchCreditNote = $controllerFetchCreditNote;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    #[Route("/datagird_credit_note_export_csv", name: "datagird-credit-note-export-csv")]
    public function exportToCsv(Request $request)
    {
        $csvData = $this->csvGetData($request);
        $fileName = $this->csvGetFileName('Credit notes');
        return $this->csvCreateStreamedResponse($csvData, $fileName);
    }

    /**
     * @param Request $request
     * @return EntityCreditNote[]
     */
    private function fetchData(Request $request)
    {
        // Fetch data
        return $this->controllerFetchCreditNote->fetchCreditNotesBySession($request->getSession());
    }

    /**
     * @return array
     */
    private function prepareHeaders():array
    {
        $exportData = [];
        $exportData['credit_note_reference'] = 'Credit note reference';
        $exportData['credit_note_creation_date'] = 'Credit note creation date';
        $exportData['credit_note_amount'] = 'Credit note amount';
        $exportData['statement_reference'] = 'Statement reference';
        $exportData['creditor_name'] = 'Creditor name';
        $exportData['debtor_name'] = 'Debtor name';
        return $exportData;
    }

    /**
     * @param EntityCreditNote[] $creditNotes
     * @return array
     */
    private function prepareData(array $creditNotes):array
    {
        /** @var EntityCreditNote $creditNote */
        foreach ($creditNotes as $creditNote) {
            $data = [];
            // Invoice data
            $data['credit_note_reference'] = $creditNote->getReference();
            $data['credit_note_creation_date'] = $creditNote->getCreationDate()->format('Y-m-d');
            $data['credit_note_amount'] = $creditNote->getAmount();

            // Statement data
            $data['statement_reference'] = $creditNote->getStatement()->getReference();

            // Creditor data
            $data['creditor_name'] = $creditNote->getStatement()->getCreditor()->getName();

            // Debtor data
            $data['debtor_name'] = $creditNote->getStatement()->getDebtor()->getName();

            $exportData[] = $data;
        }

        return $exportData;
    }
}