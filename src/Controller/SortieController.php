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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SortieController extends AbstractController
{


    public function ListAction()
    {
        return $this->render('default/index.html.twig');

    }

    public function UpdateAction($id)
    {
    }

    public function CreateAction()
    {
    }

    public function DeleteAction($id)
    {
    }

    public function InscrireSortie (int $idParticipant, int $idSortie) {
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

        return $this->render('famille/monCompteFamille.html.twig');
    }

    public function desisterSortie () {
// récupérer la sortie et IDparticipants
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieParticipant=$sortieRepository->findBy(array('idParticipant'=>$idParticipant,'idSortie'=>$idSortie));
        //recupere entity manager
        $em = $this->getDoctrine()->getManager();
        //supprimer une instance
        $em->remove($sortieParticipant);
        $em->flush();
    }
}