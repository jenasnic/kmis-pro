<?php

namespace App\Domain\Command\Front;

use App\Repository\Quote\QuoteRepository;
use App\Service\Email\EmailSender;

final class NewQuoteHandler
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly EmailSender $emailSender,
        private readonly string $mailerQuote,
    ) {
    }

    public function handle(NewQuoteCommand $command): void
    {
        $this->quoteRepository->add($command->quote, true);

        $this->emailSender->send(
            'email/new_quote.html.twig',
            $this->mailerQuote,
            [
                'firstName' => $command->quote->getContact()?->getFirstName(),
                'lastName' => $command->quote->getContact()?->getLastName(),
            ],
        );

        $this->emailSender->send(
            'email/quote_notification.html.twig',
            $command->quote->getContact()?->getEmail(),
        );
    }
}
