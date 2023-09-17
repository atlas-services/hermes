<?php

namespace App\Entity\Interfaces;

interface ContactInterface
{
    const TYPE = "formulaire";
    const CONTACT = "contact";
    const LIVREDOR = "livredor";
    const LIVREDOR_TEXTE = "Livre d'or";
    const NEWSLETTER = "newsletter";
    const NEWSLETTER_TEXTE = "Newsletter";
    const LIVREDOR_ROUTE = "livre-d-or";
    public function getFirstName();
    public function getLastName();
    public function getEmail();
    public function getContent();
}