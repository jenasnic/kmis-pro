<?php

namespace App\Domain\Model;

use App\ValueObject\Coordinates;

class Location
{
    public ?string $street = null;

    public ?string $zipCode = null;

    public ?string $city = null;

    public ?string $department = null;

    public ?string $region = null;

    public ?Coordinates $coordinates = null;
}
