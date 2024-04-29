<?php

namespace App\Controller\Exporter;

// Vendor
use Symfony\Component\HttpFoundation\StreamedResponse;

Class Csv
{
    /**
     * @param $fileName
     * @return string
     */
    public function buildFileName($fileName):string
    {
        return $fileName . '-' . date('Y-m-d-H-i-s') . '.csv';
    }

    /**
     * @param $csvData
     * @param $fileName
     * @return StreamedResponse
     */
    public function createExport($csvData, $fileName):StreamedResponse
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