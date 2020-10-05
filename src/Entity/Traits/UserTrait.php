<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\User;

trait UserTrait
{


    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
