<?php

namespace App\Domain\Command\Back;

use App\Repository\Quote\OrganizationRepository;

final class SaveOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepository $newsRepository,
    ) {
    }

    public function handle(SaveOrganizationCommand $command): void
    {
        $this->newsRepository->add($command->organization, true);
    }
}
