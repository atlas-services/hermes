<?php

namespace App\Entity\Hermes;

use App\Entity\Interfaces\ContactInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Contact implements ContactInterface
{

    const NEWSLETTER = 'Newsletter';
    const CONTACT = 'Contact';
    const LIVREDOR = "Livredor";

    private $name;
    private $subject;
    private $email;

    private $telephone;

    private $message;

    public function __construct()
    {
        $this->subject = self::CONTACT;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'email', 
            new Assert\Email(
                ['groups' => ['contact', 'newsletter', 'livredor'],
        ]));

        $metadata->addPropertyConstraint(
            'name', 
            new Assert\NotBlank(
                ['groups' => ['contact', 'livredor'],
        ]));
        $metadata->addPropertyConstraint(
            'name', 
            new Assert\Length(
                [
                'min'    => 2,
                'max'    => 40,
                'groups' => ['contact', 'livredor'],
            ]));

        $metadata->addPropertyConstraint(
            'message', 
            new Assert\Length(
                [
                'groups' => ['contact', 'livredor'],
                'min' => 2,
                'max' => 1000,
        ]));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }


}
