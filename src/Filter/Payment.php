<?php

namespace App\Filter;

class Payment
{
    private $creationDateFrom;
    private $creationDateTo;

    public function getCreationDateFrom()
    {
        return $this->creationDateFrom;
    }

    public function setCreationDateFrom($creationDateFrom): void
    {
        $this->creationDateFrom = $creationDateFrom;
    }

    public function getCreationDateTo()
    {
        return $this->creationDateTo;
    }

    public function setCreationDateTo($creationDateTo): void
    {
        $this->creationDateTo = $creationDateTo;
    }
}