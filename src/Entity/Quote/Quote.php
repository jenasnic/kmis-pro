<?php

namespace App\Entity\Quote;

use App\Enum\DurationEnum;
use App\Repository\Quote\QuoteRepository;
use App\ValueObject\Address;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    #[Assert\GreaterThan('+5 days')]
    private ?\DateTime $date = null;

    #[ORM\Column(type: 'string', length: 55, nullable: true, enumType: DurationEnum::class)]
    #[Assert\NotNull]
    private ?DurationEnum $duration = null;

    #[ORM\Embedded(class: Address::class)]
    #[Assert\Valid]
    private ?Address $location = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(min: 1, max: 99)]
    private ?int $peopleCount = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Organization $organization = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $organizationNote = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Service $service = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $serviceNote = null;

    #[ORM\OneToOne(targetEntity: Contact::class, cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private ?Contact $contact = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
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

    public function getPeopleCount(): ?int
    {
        return $this->peopleCount;
    }

    public function setPeopleCount(?int $peopleCount): self
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

        if (!$organization?->isWithNote()) {
            $this->organizationNote = null;
        }

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

        if (!$service?->isWithNote()) {
            $this->serviceNote = null;
        }

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

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->organization?->isWithNote() && empty($this->organizationNote)) {
            $context->buildViolation('This value should not be null.')
                ->atPath('organizationNote')
                ->addViolation()
            ;
        }

        if ($this->service?->isWithNote() && empty($this->serviceNote)) {
            $context->buildViolation('This value should not be null.')
                ->atPath('serviceNote')
                ->addViolation()
            ;
        }
    }
}
