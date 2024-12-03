<?php

namespace App\Entity\Hermes;

use App\Entity\Interfaces\ContactInterface;
use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\ContentTrait;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\FirstNameLastNameTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PositionTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'temoignage')]
#[ORM\Entity(repositoryClass: \App\Repository\TemoignageRepository::class)]
class Temoignage implements ContactInterface, \JsonSerializable

{
    use IdTrait;
    use ActiveTrait;
    use PositionTrait;
    use FirstNameLastNameTrait;
    use EmailTrait;
    use ContentTrait;

    public function jsonSerialize()
    {
        return
            [
                'id'   => $this->getId(),
                'firstname' => $this->getFirstName(),
                'lastname' => $this->getLastName(),
                'comment' => $this->getContent(),
            ];
    }

}
