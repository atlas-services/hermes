<?php

namespace App\Message;

class MailNotification
{

    public function __construct(

        private $entity,
        private $contact,
        private string $template,
        private array $context,
    ) {
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
