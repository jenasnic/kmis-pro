<?php

namespace App\Controller\Front;

use App\Domain\Command\Front\NewQuoteCommand;
use App\Domain\Command\Front\NewQuoteHandler;
use App\Entity\Quote\Quote;
use App\Form\Quote\QuoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuoteController extends AbstractController
{
    #[Route('/demande-de-devis', name: 'app_quote')]
    public function contact(
        Request $request,
        NewQuoteHandler $newQuoteHandler,
        TranslatorInterface $translator,
    ): Response {
        $form = $this->createForm(QuoteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Quote $quote */
            $quote = $form->getData();
            $newQuoteHandler->handle(new NewQuoteCommand($quote));

            $this->addFlash('info', $translator->trans('front.quote.page.new_quote_success'));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/quote.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
