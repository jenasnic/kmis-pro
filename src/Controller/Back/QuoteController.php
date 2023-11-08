<?php

namespace App\Controller\Back;

use App\Entity\Quote\Quote;
use App\Form\Quote\QuoteType;
use App\Repository\Quote\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuoteController extends AbstractController
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/liste-des-devis', name: 'bo_quote_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('back/quote/list.html.twig', [
            'quotes' => $this->quoteRepository->search(),
        ]);
    }

    #[Route('/detail-devis/{quote}', name: 'bo_quote_edit', methods: ['GET', 'POST', 'PATCH'])]
    public function show(Request $request, Quote $quote): Response
    {
        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        $formOptions = $isPatch ? [
            'method' => Request::METHOD_PATCH,
            'validation_groups' => false,
            'with-captcha' => false,
        ] : [
            'with-captcha' => false,
        ];

        $form = $this->createForm(QuoteType::class, $quote, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $this->quoteRepository->add($quote, true);

            $this->addFlash('info', $this->translator->trans('front.quote.page.new_quote_success'));

            return $this->redirectToRoute('bo_quote_list');
        }

        return $this->render('back/quote/edit.html.twig', [
            'form' => $form->createView(),
            'quote' => $quote,
        ]);
    }

    #[Route('/supprimer-devis/{quote}', name: 'bo_quote_delete', methods: ['DELETE'])]
    public function delete(Request $request, Quote $quote): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$quote->getId(), (string) $request->request->get('_token'))) {
            $this->quoteRepository->remove($quote, true);

            $this->addFlash('info', $this->translator->trans('back.quote.delete.success'));
        }

        return $this->redirectToRoute('bo_quote_list', [], Response::HTTP_SEE_OTHER);
    }
}
