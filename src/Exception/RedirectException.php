<?php
// Fichier : src/Exception/RedirectException.php

namespace App\Exception;

use Exception;

class RedirectException extends Exception {
    private $url;

    public function __construct(string $url) {
        parent::__construct("Redirecting to $url");
        $this->url = $url;
    }

    public function getUrl(): string {
        return $this->url;
    }
}
