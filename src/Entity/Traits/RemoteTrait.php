<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Remote;

trait RemoteTrait
{

    public function getRemote(): ?Remote
    {
        return $this->remote;
    }

    public function setRemote(?Remote $remote): void
    {
        $this->remote = $remote;
    }
}
