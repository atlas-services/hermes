<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Config;

use App\Entity\Traits\CodeTrait;
use App\Entity\Traits\ConfigTypeTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\PublishedTrait;
use App\Entity\Traits\ValueTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 * @ORM\Table(name="config")
 * Defines the properties of the Config entity to represent the configuration.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 * @Vich\Uploadable
 *
 */
class Config
{
    use IdTrait;
    use CodeTrait;
    use ConfigTypeTrait;
    use ValueTrait;
    use SummaryTrait;
    use ImageTrait;
    use UpdatedTrait;
    use PublishedTrait;
    use ActiveTrait;
    use PositionTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    public $transparent;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function __toString(): string
    {
        if(!is_null($this->summary)){
            return $this->summary;
        }
        return '';
    }


    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __isset($prop) : bool
    {
        return isset($this->$prop);
    }



}
