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
        // @todo: validate command!
        $this->quoteRepository->add($command->quote, true);

        $this->emailSender->send(
            'email/new_quote.html.twig',
            $this->mailerQuote,
            [
                'firstName' => $command->quote->getContact()?->getFirstName(),
                'lastName' => $command->quote->getContact()?->getLastName(),
            ],
        );

        /** @var string $contactEmail */
        $contactEmail = $command->quote->getContact()?->getEmail();
        $this->emailSender->send(
            'email/quote_notification.html.twig',
            $contactEmail,
        );
    }
}
