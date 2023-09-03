<?php

namespace App\Entity\Interfaces;

interface ContactInterface
{
    const TYPE = "formulaire";
    const CONTACT = "contact";
    const LIVREDOR = "livredor";
    const LIVREDOR_TEXTE = "livre d'or";
    const NEWSLETTER = "newsletter";
    const NEWSLETTER_TEXTE = "Newsletter";
    const LIVREDOR_ROUTE = "livre-d-or";
    public function getName();
    public function getEmail();
}