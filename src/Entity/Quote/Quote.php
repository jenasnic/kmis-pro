<?php

namespace App\Entity\Quote;

use App\Enum\DurationEnum;
use App\Repository\Quote\QuoteRepository;
use App\ValueObject\Address;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date;

    #[ORM\Column(type: 'string', length: 55, nullable: true, enumType: DurationEnum::class)]
    private ?DurationEnum $duration = null;

    #[ORM\Embedded(class: Address::class)]
    private ?Address $location = null;

    #[ORM\Column(type: 'integer')]
    private int $peopleCount;

    #[ORM\ManyToOne(targetEntity: OrganizationType::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?OrganizationType $organizationType = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $otherOrganizationType = null;

    #[ORM\ManyToOne(targetEntity: ServiceType::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?ServiceType $serviceType = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $otherServiceType = null;

    #[ORM\OneToOne(targetEntity: Contact::class, cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?DurationEnum
    {
        return $this->duration;
    }

    public function setDuration(?DurationEnum $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPeopleCount(): int
    {
        return $this->peopleCount;
    }

    public function setPeopleCount(int $peopleCount): self
    {
        $this->peopleCount = $peopleCount;

        return $this;
    }

    public function getOrganizationType(): ?OrganizationType
    {
        return $this->organizationType;
    }

    public function setOrganizationType(?OrganizationType $organizationType): self
    {
        $this->organizationType = $organizationType;

        return $this;
    }

    public function getOtherOrganizationType(): ?string
    {
        return $this->otherOrganizationType;
    }

    public function setOtherOrganizationType(?string $otherOrganizationType): self
    {
        $this->otherOrganizationType = $otherOrganizationType;

        return $this;
    }

    public function getServiceType(): ?ServiceType
    {
        return $this->serviceType;
    }

    public function setServiceType(?ServiceType $serviceType): self
    {
        $this->serviceType = $serviceType;

        return $this;
    }

    public function getOtherServiceType(): ?string
    {
        return $this->otherServiceType;
    }

    public function setOtherServiceType(?string $otherServiceType): self
    {
        $this->otherServiceType = $otherServiceType;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
