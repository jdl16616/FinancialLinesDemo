<?php

namespace App\Wrapper;

// Interface
use App\Interface\Get as InterfaceGet;

// Vendor
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class Session implements InterfaceGet {
    /** @var SymfonySession  */
    private $session;

    public function __construct(SymfonySession $session) {
        $this->session = $session;
    }

    public function get(string $paramName) {
        return $this->session->get($paramName);
    }
}