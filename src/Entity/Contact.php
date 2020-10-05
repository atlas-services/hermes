<?php

namespace App\Entity;

use App\Entity\Interfaces\ContactInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Contact implements ContactInterface
{
    /**
     * @Assert\Length(max=40)
     * @Assert\NotBlank(message = "Le nom doit être renseigné")
     */
    private $name;

    /**
     * @Assert\NotBlank(message = "L'email doit être renseigné")
     * @Assert\Email(message = "L'email n'est pas correct")
     */
    private $email;

    private $telephone;

    /**
     * @Assert\Length(max=1000)
     */
    private $message;

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
    public function setMessage($message): void
    {
        $this->message = $message;
    }

}
