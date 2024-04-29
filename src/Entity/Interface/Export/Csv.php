<?php

namespace App\Entity\Interface\Export;

interface Csv
{
    static function csvGetHeadings(): string;
    public function csvGetData(): string;
}