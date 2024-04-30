<?php

namespace App\Controller\Export\Trait;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait Csv
{
    private function csvGetData(Request $request)
    {
        $exportHeaders = $this->prepareHeaders();
        $csvData = implode(',', $exportHeaders) . PHP_EOL;

        // Data
        $dataArray = $this->fetchData($request);
        if (!empty($dataArray)) {
            $exportData = $this->prepareData($dataArray);
            foreach ($exportData as $entityData) {
                $csvData .= implode(',', $entityData) . PHP_EOL;
            }
        }

        return $csvData;
    }

    /**
     * @param $fileName
     * @return string
     */
    private function csvGetFileName($fileName):string
    {
        return $fileName . '-' . date('Y-m-d-H-i-s') . '.csv';
    }

    /**
     * @param $csvData
     * @param $fileName
     * @return StreamedResponse
     */
    private function csvCreateStreamedResponse($csvData, $fileName):StreamedResponse
    {
        // Create a StreamedResponse to output the CSV data
        $response = new StreamedResponse();
        $response->setCallback(function () use ($csvData) {
            echo $csvData;
        });

        // Set response headers for CSV download
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $fileName));

        return $response;
    }
}