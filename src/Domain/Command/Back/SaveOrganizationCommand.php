<?php

namespace App\Domain\Command\Back;

use App\Entity\Quote\Organization;

class SaveOrganizationCommand
{
    public function __construct(public Organization $organization)
    {
    }
}
