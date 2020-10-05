<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 13/03/20
 * Time: 13:57
 */

namespace App\Entity;

use App\Entity\Traits\ContentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\UserTrait;


abstract class AbstractContent
{
    use IdTrait;
    use NameTrait;
    use ContentTrait;
    use ImageTrait;
    use UpdatedTrait;
    use ActiveTrait;
    use UserTrait;
}
