<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 11:41
 */
namespace App\Controller;


use App\Entity\EtatSortie;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    function Index()
    {
        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);




        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());
            $listeDesSorties = $sortieRepository->findListSortieUser();
        } else {
<<<<<<< HEAD
            $maliste = "";
            //Find la liste de Sortie
            $listeDesSorties = $sortieRepository->findListSortie();
=======
            $maliste = [];
>>>>>>> a29a5081398c69edee9ef80598897c44a9696071
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
    }

}