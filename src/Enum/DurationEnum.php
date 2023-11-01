<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum DurationEnum: string implements TranslatableEnumInterface
{
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->getTranslationKey());
    }

    public function getTranslationKey(): string
    {
        return 'enum.duration.'.$this->name;
    }

    case TWO_HOURS = 'TWO_HOURS';
    case HALF_DAY = 'HALF_DAY';
    case DAY = 'DAY';
    case OTHER = 'OTHER';
}
