<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Offer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuctionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * controlleur lié aux enchères de l'utilisateur
 */
class MyAccountController extends AbstractController
{
    /**
     * méthode qui récupère les enchères en ventes, en cours d'achat et gagné de l'utilisateur
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/my_auctions', name: 'app_my_auctions')]
    public function index(ManagerRegistry $doctrine): Response{
        $repository = $doctrine->getRepository(Auction::class);
        $repositoryOffer = $doctrine->getRepository(Offer::class);
        $currentOffersRecup = $repositoryOffer->findBy(['bidder'=>$this->getUser()]);
        $currentOffers = new ArrayCollection();
        foreach ($currentOffersRecup as $currentOffer){
            if($currentOffer->getAuction()->getEndDate() > new \DateTimeImmutable()){
                $currentOffers->add($currentOffer);
            }
        }
        $auctionWin = $repository->findBy(['winner'=>$this->getUser()]);
        $auctionBeatmaker = $repository->findBy(['beatmaker'=>$this->getUser()]);
        return $this->render('auction/MyAuctions.html.twig', [
            'currentOffers'=> $currentOffers,
            'auctionWin'=>$auctionWin,
            'auctionBeatmaker'=>$auctionBeatmaker,
        ]);
    }
}
