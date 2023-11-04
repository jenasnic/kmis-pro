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

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Organization $organization = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $organizationNote = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Service $service = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $serviceNote = null;

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

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganizationNote(): ?string
    {
        return $this->organizationNote;
    }

    public function setOrganizationNote(?string $organizationNote): self
    {
        $this->organizationNote = $organizationNote;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getServiceNote(): ?string
    {
        return $this->serviceNote;
    }

    public function setServiceNote(?string $serviceNote): self
    {
        $this->serviceNote = $serviceNote;

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
