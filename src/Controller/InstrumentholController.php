<?php

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use App\Entity\Auction;
use App\Entity\Notification;
use App\Entity\User;
use App\Enum\MessageNotification;
use App\Repository\NotificationRepository;
use App\Model\SearchData;
use App\Form\SearchType;
use App\Form\CategoryForm;
use App\Form\InstrumentForm;
use App\Form\MoodsForm;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AuctionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * controller principal
 * gère la page d'accueil, la liste d'enchères
 */
class InstrumentholController extends AbstractController
{
    /**
     * méthode qui redirige vers la page d'accueil
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/instrumenthol', name: 'app_instrumenthol')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //on prend le repository associé à l'entité Auction
        $repository = $doctrine->getRepository(Auction::class);
        //on créer un objet search data qui permet de chercher/filtrer les entités
        $searchData = new SearchData();
        $searchData->id_user_current = Uuid::fromString($this->getUser()->getUserIdentifier())->toString();
        //on récupère une liste d'enchères en fonction des vérifications passées par le search data 
        //qui permet d'afficher les enchères en fonction de leur date de fin
        $auctions = $repository->findBySearch($searchData);
        //on passe la liste d'enchère à la page d'accueil pour afficher les enchères qui se terminent bientôt
        return $this->render('instrumenthol/index.html.twig', [
            'auctions' => $auctions,
        ]);
    }

    /**
     * Méthode qui permet de lister et filtrer les enchères
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    #[Route('/instrumenthol/auctions_list', name: 'app_auctions_list')] 
    public function showListAuctions(ManagerRegistry $doctrine, Request $request): Response
    {
        //on récupère le répertoire lié à la classe enchère
        $repository = $doctrine->getRepository(Auction::class);
        //on récupère toutes les enchères
        $auctions = $repository->findAll();


        //Recherche beat
        $searchData = new SearchData();
        //on créer un formulaire pour trouver le beat entré dans la barre de recherche
        $form_search = $this->createForm(SearchType::class, $searchData);
        //on créer un formulaire pour filtrer les catégories
        $form_filter_category = $this->createForm(CategoryForm::class, $searchData);
        //on créer un formulaire pour filtrer les instruments
        $form_filter_instrument= $this->createForm(InstrumentForm::class, $searchData);
        //on créer un formulaire pour les moods
        $form_filter_moods=$this->createForm(MoodsForm::class,$searchData);
        //traitement des requêtes du formulaire
        $form_search->handleRequest($request);
        $form_filter_category->handleRequest($request);
        $form_filter_instrument->handleRequest($request);
        $form_filter_moods->handleRequest($request);
        //Récuperation page
        $page = $request->get("page");
        if($page){
            $searchData->page =$page;
        }
        //Récupération des filtres category
        $filters_cat = $request->get("category_form");
        if(!$filters_cat || !$filters_cat['category']){
            $category=[];
        }else if(!is_array($filters_cat['category'])){
            $category=[$filters_cat['category']];
        }else {
            $category=$filters_cat['category'];
        }
        $searchData->setCategory($category);


        //Récupération des filtres instruments
        $filters_instru = $request->get("instrument_form");
        if(!$filters_instru || !$filters_instru['instru']){
            $instrument=[];
        }else if(!is_array($filters_instru['instru'])){
            $instrument=[$filters_instru['instru']];
        }else {
            $instrument=$filters_instru['instru'];
        }
        $searchData->setInstrument($instrument);
        //Récupération des filtres moods
        $filters_mood = $request->get("moods_form");
        if(!$filters_mood || !$filters_mood['moods']){
            $moods=[];
        }else if(!is_array($filters_mood['moods'])){
            $moods=[$filters_mood['moods']];
        }else {
            $moods=$filters_mood['moods'];
        }
        $searchData->setMoods($moods);
        //Récupération de la recheche
        $search_form =$request->get("search");
        if(!$search_form || !$search_form['q']){
            $search ='';
        }else{
            $search=$search_form['q'];
        }
        $searchData->setSearch($search);
        //Récupération de l'user courrant
        $searchData->id_user_current = Uuid::fromString($this->getUser()->getUserIdentifier())->toString();
        //Recherche de toutes les enchères avec les filtres
        $auctions = $repository->findBySearch($searchData);
        // On vérifie si on a une requête Ajax
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('auction/_auction.html.twig',[
                    'auctions' => $auctions,
                ])
            ]);
        }
    
        //Check les categories selectionné
        /*
        foreach ($searchData->getCategory() as $key => $value){
            $form_filter_category->get('c')->get('0')->setData(true);
        }*/ 
        //on renvoie les informations du formulaire en GET
        return $this->render('auction/list_auctions.html.twig', [
            'form_search' => $form_search->createView(),
            'form_filtercategory'=>$form_filter_category->createView(),
            'form_filter_instrument'=>$form_filter_instrument->createView(),
            'form_filter_moods'=>$form_filter_moods->createView(),
            'auctions' => $auctions,
        ]);
    }
}
