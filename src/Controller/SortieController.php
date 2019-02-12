<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 13:48
 */

namespace App\Controller;


use App\Entity\Sortie;

use App\Entity\User;

use App\Form\SortieType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SortieController extends AbstractController
{


    public function List()
    {

        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);

        //Find la liste de Sortie
        $listeDesSorties = $sortieRepository->FindListSortie();

        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties
        ));

    }

    public function Update($id)
    {
    }

    public function Create(Request $request)
    {
        $sortie = new Sortie();

        $formSortie = $this->createForm(SortieType::class,$sortie);
        $formSortie->handleRequest($request);

        if($formSortie->isSubmitted() && $formSortie->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            //on demande à Doctrine de sauvegarder notre instance
            $em->persist($sortie);
            //on exécute les requêtes
            $em->flush();

            //crée un message flash à afficher sur la prochaine page
            $this->addFlash('success', 'Merci pour votre participation !');

            //redirige vers la page de détails de cette question
            return $this->redirectToRoute('sortie_detail',
                ['id' => $sortie->getId()]
            );

        }

       return $this->render('sortie/create.html.twig', array(
           'formSortie' => $formSortie->createView()
       ));
    }

    public function Delete($id)
    {
    }

    public function Detail($id)
    {

    }

    public function inscrireSortie (int $idParticipant, int $idSortie) {
        $sortie= new Sortie();
        $participant = new User();
        //associer les objects sortie et user entre eux


        //TODO gérer l'accès en fonction des roles
        //TODO gérer la récupéréation de l'ID participant et id sortie

        // récupérer l'objet sortie en entier
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepository->findOneBy(['id'=>$idSortie]);

        //récupérer l'objet participant en entier
        $participantRepository = $this->getDoctrine()->getRepository(User::class);
        $participant=$participantRepository->findOneBy(['id'=>$idParticipant]);

        $sortie->addUser($participant);
        $participant->addSortiesInscrit($sortie);

        // récupére l'objet entity manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($sortie);
        $em->persist($participant);

        //ajouter en base
        $em->flush();

        return $this->redirectToRoute('index');
    }

    public function desisterSortie (int $idParticipant, int $idSortie) {
        $sortie= new Sortie();
        $participant = new User();

        // récupérer l'objet sortie en entier
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepository->findOneBy(['id'=>$idSortie]);

        //récupérer l'objet participant en entier
        $participantRepository = $this->getDoctrine()->getRepository(User::class);
        $participant=$participantRepository->findOneBy(['id'=>$idParticipant]);

        $sortie->removeUser($participant);
        $participant->removeSortiesInscrit($sortie);

        // récupére l'objet entity manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($sortie);
        $em->persist($participant);

        //ajouter en base
        $em->flush();

        return $this->redirectToRoute('index');


    }






}