<?php

namespace App\Entity\Traits;

trait DateTimeAwareTrait
{
    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getCurrentDateTime(): \DateTime
    {
        return new \DateTime();
    }
}