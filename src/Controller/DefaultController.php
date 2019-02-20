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
use App\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{

    function Index(Request$request, $idSite = null)
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        //création du formulaire pour les sites
        $siteForm=$this->createForm(SiteType::class);
        $siteForm->handleRequest($request);
        if($siteForm->isSubmitted()&& $siteForm->isValid()){


            }
        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        //Find la liste de Sortie
        $listeDesSorties = $sortieRepository->findListSortie();

        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());
        } else {
            $maliste = "";
        }


        $arrayParticipant = array();
        foreach($listeDesSorties as $sortie){
            $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
            $arrayParticipant[$sortie->getId()] = $nombreParticipant;
        }


        ////////////////////////////////////////////////////////////////////////
        //recherche des sorties en fonction des sites
        //////////////////////////////////////////////////////////////////////////
        $site=new Site();
        //dd($idSite);
        if ($idSite){

            $siteRepo=$this->getDoctrine()->getRepository(Site::class);
            $site=$siteRepo->find($idSite);
            //$siteRepo->persist($site);

        }
        $nomSite=$site->getNom();
        //var_dump('le nom de mon site =>', $nomSite); //OK
        $listeDesSorties =$sortieRepository->findListSortie($site);
         //dd($listeDesSorties);
        ///////////////////////fin recherche par site///////////////////////////////////

        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));
    }




}