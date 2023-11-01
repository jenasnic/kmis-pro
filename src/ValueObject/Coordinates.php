<?php

namespace App\ValueObject;

class Coordinates
{
    public float $longitude;

    public float $latitude;

    public function __construct(float $longitude, float $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }
}
