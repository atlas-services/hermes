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

    private $firstname;
    private $lastname;
    private $subject;
    private $email;

    private $telephone;

    private $content;

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
            'firstname', 
            new Assert\NotBlank(
                ['groups' => ['contact', 'livredor'],
        ]));
        $metadata->addPropertyConstraint(
            'firstname', 
            new Assert\Length(
                [
                'min'    => 2,
                'max'    => 40,
                'groups' => ['contact', 'livredor'],
            ]));

        $metadata->addPropertyConstraint(
            'lastname', 
            new Assert\NotBlank(
                ['groups' => ['contact', 'livredor'],
        ]));
        $metadata->addPropertyConstraint(
            'lastname', 
            new Assert\Length(
                [
                'min'    => 2,
                'max'    => 40,
                'groups' => ['contact', 'livredor'],
            ]));

        $metadata->addPropertyConstraint(
            'content', 
            new Assert\Length(
                [
                'groups' => ['contact', 'livredor'],
                'min' => 2,
                'max' => 1000,
        ]));
        $metadata->addPropertyConstraint(
            'content', 
            new Assert\NotBlank(
                [
                'groups' => ['contact', 'livredor'],
        ]));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return sprintf('%s , %s', $this->firstname, $this->lastname);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $name
     */
    public function setFirstName($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $name
     */
    public function setLastName($lastname): void
    {
        $this->lastname = $lastname;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

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
