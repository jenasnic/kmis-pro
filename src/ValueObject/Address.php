<?php

namespace App\ValueObject;

use App\Service\Localization\DepartmentResolver;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Embeddable
 */
class Address
{
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $street;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    #[Assert\NotBlank]
    private ?string $zipCode;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $city;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    private ?string $department;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    private ?string $region;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    private ?string $country;

    public function __construct(
        ?string $street = null,
        ?string $zipCode = null,
        ?string $city = null,
        ?string $department = null,
        ?string $region = null,
        ?string $country = null,
    ) {
        $this->street = $street;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->department = $department;
        $this->region = $region;
        $this->country = $country;

        if ($this->zipCode) {
            if (null === $this->department) {
                $this->department = DepartmentResolver::departmentForZip($this->zipCode);
            }

            if (null === $this->region) {
                $this->region = DepartmentResolver::regionForZip($this->zipCode);
            }
        }
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }
}
