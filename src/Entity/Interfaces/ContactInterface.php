<?php

namespace App\Entity\Interfaces;

interface ContactInterface
{
    const CONTACT = "contact";
    const LIVREDOR = "livredor";
    const LIVREDOR_TEXTE = "livre d'or";
    const LIVREDOR_ROUTE = "livre-d-or";
    public function getName();
    public function getEmail();
}