<?php

namespace App\Entity\Quote;

use App\Enum\GenderEnum;
use App\Helper\StringHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 55, nullable: true, enumType: GenderEnum::class)]
    private ?GenderEnum $gender = null;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[\d\s]{14}$/')]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        if (!empty($firstName)) {
            $this->firstName = StringHelper::capitalizeFirstname($firstName);
        }

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        if (!empty($lastName)) {
            $this->lastName = mb_strtoupper($lastName);
        }

        return $this;
    }

    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(?GenderEnum $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}
