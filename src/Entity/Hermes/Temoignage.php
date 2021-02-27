<?php

namespace App\Entity\Hermes;

use App\Entity\Interfaces\ContactInterface;
use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\ContentTrait;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TemoignageRepository")
 * @ORM\Table(name="temoignage")
 */
class Temoignage implements ContactInterface, \JsonSerializable

{
    use IdTrait;
    use ActiveTrait;
    use PositionTrait;
    use NameTrait;
    use EmailTrait;
    use ContentTrait;

    public function jsonSerialize()
    {
        return
            [
                'id'   => $this->getId(),
                'name' => $this->getName(),
                'comment' => $this->getContent(),
            ];
    }

}
