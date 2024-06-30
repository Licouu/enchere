<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WhoAreWeController extends AbstractController
{
    #[Route('/instrumenthol/info/whoAreWe', name: 'app_who_we_are')]
    public function index(): Response
    {
        return $this->render('auction/WhoAreWe.html.twig', [
            'controller_name' => 'WhoWeAreController',
        ]);
    }
}
