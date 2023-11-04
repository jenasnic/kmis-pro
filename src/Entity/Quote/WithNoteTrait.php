<?php

namespace App\Entity\Quote;

use Doctrine\ORM\Mapping as ORM;

trait WithNoteTrait
{
    #[ORM\Column(type: 'boolean')]
    private bool $withNote = false;

    public function isWithNote(): bool
    {
        return $this->withNote;
    }

    public function setWithNote(bool $withNote): self
    {
        $this->withNote = $withNote;

        return $this;
    }
}
