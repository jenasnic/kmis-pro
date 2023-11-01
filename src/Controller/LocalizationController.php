<?php

namespace App\Controller;

use App\Service\Localization\LocalizationProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocalizationController extends AbstractController
{
    public function __construct(
        private readonly LocalizationProvider $localizationProvider,
        private readonly string $localizationKey,
    ) {
    }

    #[Route('/localization/{search}', name: 'app_localization')]
    public function localization(Request $request, string $search): Response
    {
        if (
            !$request->headers->has('authorization')
            || $request->headers->get('authorization') !== $this->localizationKey
        ) {
            throw $this->createAccessDeniedException();
        }

        $locations = $this->localizationProvider->search($search, true);

        return new JsonResponse($locations);
    }
}
