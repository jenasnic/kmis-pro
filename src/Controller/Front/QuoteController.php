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
    #[Route('/demande-de-devis', name: 'app_quote', methods: ['GET', 'POST', 'PATCH'])]
    public function contact(
        Request $request,
        NewQuoteHandler $newQuoteHandler,
        TranslatorInterface $translator,
    ): Response {
        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        $formOptions = $isPatch ? [
            'method' => Request::METHOD_PATCH,
            'validation_groups' => false,
        ] : [];

        $quote = new Quote();
        $form = $this->createForm(QuoteType::class, $quote, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $newQuoteHandler->handle(new NewQuoteCommand($quote));

            $this->addFlash('info', $translator->trans('front.quote.page.new_quote_success'));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/quote.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
