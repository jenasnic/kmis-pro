<?php

namespace App\Domain\Command\Back;

use App\Entity\Quote\Service;

class SaveServiceCommand
{
    public function __construct(public Service $service)
    {
    }
}
