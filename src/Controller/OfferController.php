<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Notification;
use App\Entity\Offer;
use App\Entity\User;
use App\Enum\MessageNotification;
use App\Form\OfferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class OfferController extends AbstractController
{
    /**
     * méthode qui créer une offre lié à une enchère (identifié par son identifiant dans la route)
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Auction $auction
     * @return Response
     */
    #[Route('/auction/{id}', name: 'app_offer')]
    public function createOffer(MailerInterface $mailer, ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager, Auction $auction): Response
    {
        $isBeatmaker = ($this->getUser() == $auction->getBeatmaker());
        //on appelle le repository de la classe offre
        $repoOffer = $doctrine->getRepository(Offer::class);
        //on récupère l'offre lié au prix le plus élevé
        $offer = $repoOffer->findByMaxPrice();
        if($auction->getEndDate() <= new \DateTimeImmutable()){
            if($offer->getAuction()->getWinner() == $this->getUser()){
                return $this->render('auction/offer.html.twig', [
                    'auction' => $auction,
                    'finDeLenchere' => true,
                    'winner' => true,
                    'isBeatmaker'=> $isBeatmaker,
                ]);
            }
            return $this->render('auction/offer.html.twig', [
                'auction' => $auction,
                'finDeLenchere' => true,
                'winner' => false,
                'isBeatmaker' => $isBeatmaker,
            ]);
        }
        //on appelle le repository de la classe offre afin de trouver l'offre correspondant à l'enchère
        // et à l'utilisateur connecté
        $offer = $doctrine->getRepository(Offer::class)->findOneBy([
            'auction' => $auction,
            'bidder' => $this->getUser(),
        ]);
        //on initialise un booléen à true et il permettra de voir si l'offre existait déjà
        $exist = true;
        //si il n'existait pas d'offre
        if (!$offer) {
            //dans ce cas sauvegarde dans le booléen qui indique que c'est une nouvelle offre
            $exist = false;
            //et on créer l'offre
            $offer = new Offer();
        }
        //si l'utilisateur n'est pas éliminé
        if (!$offer->isEliminated()) {
            //on créer un formulaire auquel il pourra soumettre une offre
            $form = $this->createForm(OfferFormType::class, $offer);
            $form->handleRequest($request);
            //vérification que le formulaire est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                //si le formulaire n'exisait pas on rajoute l'enchère et l'utilisateur à l'offre
                if (!$exist) {
                    $offer->setAuction($auction);
                    $offer->setBidder($this->getUser());
                }
                //on rajoute la nouvelle date de l'offre
                $offer->setDate(new \DateTime());
                //si le prix est plus élevé
                if ($offer->getPrice() > $auction->getMaxPrice()) {
                    //alors on modifie le prix le plus élevé de l'enchère
                    $auction->setMaxPrice($offer->getPrice());
                    //et on crée une notification qui sera envoyé au précédent premier
                    $this->addNotification($mailer, $doctrine, $entityManager, MessageNotification::messageYouAreFirst, $offer);
                } else {
                    //sinon le prix est inférieur ou égal au prix maximal, donc c'est une tentative échouée pour l'enchérisseur
                    //si l'utilisateur avait déjà soumit une offre (donc que offre était déjà crée)
                    if ($exist) {
                        //alors on élimine l'utilisateur (selon notre règle d'enchère)
                        $offer->setIsEliminated(true);
                        $this->addNotification($mailer, $doctrine, $entityManager, MessageNotification::messageYouAreEliminated, $offer);
                    } else {
                        $this->addNotification($mailer, $doctrine, $entityManager, MessageNotification::messageYouAreNotFirst, $offer);  
                    }
                }
                //on enregistre l'offre dans la base de données
                $entityManager->persist($offer);
                $entityManager->flush();
                //on redirige vers la page d'offre
                return $this->redirectToRoute('app_offer', array('id'=>$auction->getId()));
            }
            return $this->render('auction/offer.html.twig', [
                'offerForm' => $form->createView(),
                'auction' => $auction,
                'offer' => $offer,
                'finDeLenchere' => false,
                'winner'=>false,
                'isBeatmaker'=>$isBeatmaker,
            ]);
        }
        //dans le cas où l'utilisateur est éliminé, on n'envoie pas le formulaire
        return $this->render('auction/offer.html.twig', [
            'auction' => $auction,
            'offer' => $offer,
            'finDeLenchere' => false,
            'winner'=>false,
            'isBeatmaker'=>$isBeatmaker,
        ]);
    }

    #[Route('/auction/win/{id}', name: 'app_offer_win')]
       public function showAuction(Auction $auction): Response
    {
        return $this->render('auction/win.html.twig', [
            'auction' => $auction,
        ]);
    }


    /**
     * méthode qui créer une notification
     * @param ManagerRegistry $doctrine
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function addNotification(MailerInterface $mailer, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, MessageNotification $messageNotification, Offer $offer)
    {
        //on créer une notification pour l'utilisateur actuel
        $this->createNotification($mailer, $this->getUser(), $offer, $entityManager, $messageNotification);
        if($messageNotification == MessageNotification::messageYouAreFirst) {
            //on appelle le repository de la classe offre
            $repoOffer = $doctrine->getRepository(Offer::class);
            //on récupère l'offre lié au prix le plus élevé
            $offer = $repoOffer->findByMaxPrice();
            //si l'offre existe alors on créer la notification
            if ($offer) {
                //on récupère l'ancien premier de l'enchère
                $user = $offer->getBidder();
                //on vérifie que l'ancien premier n'est pas le premier actuel
                if ($user->getUserIdentifier() !== $this->getUser()->getUserIdentifier()) {
                    //dans le cas on créer une notification qui informera à l'ancien gagnant qu'il n'est plus premier
                    $this->createNotification($mailer, $user, $offer, $entityManager, MessageNotification::messageYouAreNotLongerFirst);
                }
            }
        }
    }

    public function createNotification(MailerInterface $mailer, User $user, Offer $offer, EntityManagerInterface $entityManager, MessageNotification $message)
    {
        //créer un objet notification
        $notif = new Notification();
        //on set les informations associées
        $notif->setDate(new \DateTimeImmutable());
        $notif->setToUser($user);
        $notif->setMessage($message);
        $notif->setForAuction($offer->getAuction());
        //on prépare les notifications
        $entityManager->persist($notif);
        //on enregistre dans la base de données
        $entityManager->flush();
        //on appelle la méthode pour envoyer un email
        $this->sendEmail($user->getUsername(), $mailer, $user->getEmail(), $message->value);
    }
    
    public function sendEmail(string $username, MailerInterface $mailer, string $emailDest, string $messageNotification)
    {
        //créer et envoie un email via sendgrid
        $email = (new Email())
            ->from(new Address('matthew83@outlook.fr', 'Instrumenthol'))
            ->to($emailDest)
            ->subject("Auction")
            ->text($messageNotification)
            ->html("<p>Hello $username, $messageNotification</p>");
        $mailer->send($email);
    }
}
