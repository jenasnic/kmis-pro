<?php

namespace App\Domain\Command\Back;

use App\Repository\Quote\ServiceRepository;

final class SaveServiceHandler
{
    public function __construct(
        private readonly ServiceRepository $newsRepository,
    ) {
    }

    public function handle(SaveServiceCommand $command): void
    {
        $this->newsRepository->add($command->service, true);
    }
}
