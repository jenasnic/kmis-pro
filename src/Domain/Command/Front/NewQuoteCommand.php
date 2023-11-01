<?php

namespace App\Domain\Command\Front;

use App\Entity\Quote\Quote;
use Symfony\Component\Validator\Constraints as Assert;

class NewQuoteCommand
{
    #[Assert\Valid]
    public Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }
}
