<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Beat;
use App\Enum\Instrument;
use App\Enum\Mood;
use App\Form\AuctionFormType;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class AuctionController extends AbstractController
{
    /**Méthode de création de l'enchère
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/auction', name: 'app_auction_form')]
    public function createAuction(Request $request, EntityManagerInterface $entityManager): Response
    {
        //création des objets enchères et beats
        $auction = new Auction();
        $beat = new Beat();
        //création du formulaire associé à l'enchère
        $form = $this->createForm(AuctionFormType::class, $auction);
        $form->handleRequest($request);
        //on vérifie que l'enchère est soumise et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            //pour enregistrer les données dont ceux des images
            $uploadImageFile = $form['imageFile']->getData();
            //des musiques
            $uploadMusicFile = $form['musicFile']->getData();
            //on prend l'extension des fichiers
            $extensionMusic = $uploadMusicFile->guessExtension();
            //on vérifie que l'extension existe bien
            if (!$extensionMusic) {
                // extension cannot be guessed
                $extensionMusic = 'bin';
            }
            //on choisit un nombre random
            $fileMusicName = rand(1, 99999).'.'.$extensionMusic;
            //on déplace le fichier dans le répertoire assets associé
            $uploadMusicFile->move('assets/musics', $fileMusicName);
            //on prend l'extension de l'image
            $extensionImage = $uploadImageFile->guessExtension();
            if (!$extensionImage) {
                // extension cannot be guessed
                $extensionImage = 'bin';
            }
            $fileImageName = rand(1, 99999).'.'.$extensionImage;
            $uploadImageFile->move('assets/images', $fileImageName);
            //on set l'URL de l'image
            $beat->setImage($fileImageName);
            //de la musique
            $beat->setMusic($fileMusicName);
            //ainsi que des autres attribue associé au beat
            $beat->setName($form['name']->getData());
            $beat->setCategory($form['categorie']->getData());
            $beat->setMood($form['mood']->getData());
            //on set les attributs de l'enchère
            $auction->setBeatmaker($this->getUser());
            $auction->setBeat($beat);
            $auction->setCreatedAt(new \DateTimeImmutable());
            //l'entity manager prépare l'entité qu'on a crée
            $entityManager->persist($auction);
            //et l'enregistre
            $entityManager->flush();
            //on est redirigé vers l'accueil
            return $this->redirectToRoute('app_instrumenthol');
        }
        //si on a pas soumis le formulaire ou qu'il n'est pas valide, on est redirigé vers le formulaire
        return $this->render('auction/auctionForm.html.twig', [
            'auctionForm' => $form->createView(),
        ]);
    }
}
