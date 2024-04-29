<?php

namespace App\Wrapper;

// Interface
use App\Interface\Get as InterfaceGet;

// Vendor
use Symfony\Component\HttpFoundation\InputBag as SymfonyInputBag;

class InputBag implements InterfaceGet {
    /** @var SymfonyInputBag  */
    private $inputBag;

    public function __construct(SymfonyInputBag $inputBag) {
        $this->inputBag = $inputBag;
    }

    public function get(string $paramName) {
        return $this->inputBag->get($paramName);
    }
}