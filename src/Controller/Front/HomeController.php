<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/protections-des-donnees', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('front/privacy.html.twig');
    }
}
