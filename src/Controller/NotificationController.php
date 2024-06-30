<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Controlleur lié aux notifications d'envois
 */
class NotificationController extends AbstractController
{
    /**
     * méthode appellée en ajax qui renvoie un JSON associé aux notifications de l'utilisateur
     * @param ManagerRegistry $doctrine
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/notification', name: 'app_instrumenthol_notification')]
    public function clicNotification(ManagerRegistry $doctrine, SerializerInterface $serializer) : Response{
        //on récupère l'utilisateur actuel
        $user = $this->getUser();
        //on vérifie que l'utilisateur existe
        if(!$user){
            //sinon on indique qu'il n'est pas autorisé à y accéder
            return $this->json(['code' => 403, 'error' => "Unauthorized"], 403);
        }
        //on récupère la repository associé à la notification
        $repository = $doctrine->getRepository(Notification::class);
        //on trouve les notifications lié à l'utilisateur
        $notifications = $repository->findBy(['toUser' => $user]);
        //on créer un json stockant les informations nécessaires pour la notification
        $jsonContent = $serializer->serialize($notifications,'json', ['groups' => ['user', 'beat', 'auction']]);
        //on renvoie le JSON associé
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * méthode appellé en ajax qui supprime les notifications lié à l'utilisateur
     * appellé lors du clique sur la croix de la notification
     * @param Notification $notification
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $doctrine
     * @param SerializerInterface $serializer
     * @return Response
     */
     #[Route('/notification/{id}', name: 'app_instrumenthol_delete_notification')]
    public function suppressionNotification(Notification $notification, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, SerializerInterface $serializer) : Response{
        //on récupère l'utilisateur
        $user = $this->getUser();
        if(!$user){
            return $this->json(['code' => 403, 'error' => "Unauthorized"], 403);
        }
        //on supprime la notification récupére dans l'URL
        $entityManager->remove($notification);
        //on le sauvegarde
        $entityManager->flush();
        //on récupère le repository associé aux notifications
        $repository = $doctrine->getRepository(Notification::class);
        //on cherche les notifications associé à l'utilisateur
        $notifications = $repository->findBy(['toUser' => $user]);
        //on stocke les infos en JSON
        $jsonContent = $serializer->serialize($notifications,'json', ['groups' => ['user', 'beat', 'auction']]);
        //on renvoie le JSON
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * méthode qui renvoie le nombre de notification
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/nbnotif', name: 'app_instrumenthol_nb_notification')]
    public function nbNotif(ManagerRegistry $doctrine) : Response{
        //on récupère l'utilisateur connecté
        $user = $this->getUser();
        //on récupère le repository associé aux notifications
        $repository = $doctrine->getRepository(Notification::class);
        //on compte les notifications lié à l'utilisateur connecté
        $nbNotif = $repository->count(['toUser' => $user]);
        //on renvoie un JSON correspondant
        return $this->json(['code' => 200, 'nbNotif' => $nbNotif], 200);
    }
}
