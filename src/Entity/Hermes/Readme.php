<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Hermes;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\ContentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\ActiveTrait;


#[ORM\Table(name: 'readme')]
#[ORM\Entity]
class Readme
{
    use IdTrait;
    use NameTrait;
    use SummaryTrait;
    use ContentTrait;
    use UpdatedTrait;
    use ActiveTrait;

    /**
     * @var \DateTime
     */
    #[ORM\Column(type: 'datetime')]
    private $publishedAt;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

}
