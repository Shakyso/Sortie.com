<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 11:41
 */
namespace App\Controller;


use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SearchAccueilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{

    function Index()
    {

        //création du formulaire de recherche
        $searchForm=$this->createForm(SearchAccueilType::class);
        $searchForm->handleRequest($request);





        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);

        $listeDesSorties = $sortieRepository->findListSortieUser();
        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());

        } else {
            $maliste = "";
            //Find la liste de Sortie
        //    $listeDesSorties = $sortieRepository->findListSortie();
        }
        $arrayParticipant = array();
        foreach($listeDesSorties as $sortie){
            $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
            $arrayParticipant[$sortie->getId()] = $nombreParticipant;
        }
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));

        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'searchAccueilForm'=>$searchForm->createView(),
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));
    }

    function selectIndex(Request $request, $idSite = null)
    {


        /*
        //envoi du form à la page
        return $this->render('default/index.html.twig', array(
            'searchAccueilForm'=>$searchForm->createView(),
            //'listeDesSorties' => $listeDesSorties,
            //'nombreParticipant' => $arrayParticipant,
            //'maliste' => $maliste
        ));


        /*
        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);

        $listeDesSorties = $sortieRepository->findListSortieUser();
        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());

        } else {
            $maliste = "";
            //Find la liste de Sortie
            //    $listeDesSorties = $sortieRepository->findListSortie();
        }
        $arrayParticipant = array();
        foreach($listeDesSorties as $sortie){
            $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
            $arrayParticipant[$sortie->getId()] = $nombreParticipant;
        }
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));

        ////////////////////////////////////////////////////////////////////////
        //recherche des sorties en fonction des sites
        //////////////////////////////////////////////////////////////////////////
        $site=new Site();
        dd($idSite);
        if ($idSite){

            $siteRepo=$this->getDoctrine()->getRepository(Site::class);
            $site=$siteRepo->find($idSite);
            //$siteRepo->persist($site);
        }
        $listeDesSorties =$sortieRepository->selectListSortie($site);
        ///////////////////////fin recherche par site///////////////////////////////////
*/
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));
    }



}