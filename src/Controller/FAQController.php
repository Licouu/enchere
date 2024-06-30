<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* controller qui redirige vers le template de FAQ
*/
class FAQController extends AbstractController
{
    #[Route('/instrumenthol/info/FAQ', name: 'app_faq')]
    public function index(): Response
    {
        return $this->render('auction/faq.html.twig', [
            'controller_name' => 'FAQController',
        ]);
    }
}
