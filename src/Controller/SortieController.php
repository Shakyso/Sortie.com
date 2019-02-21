<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 13:48
 */

namespace App\Controller;


use App\Entity\EtatSortie;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\User;
use App\Entity\Ville;

use App\Form\SortieLieuVilleType;
use App\Form\SortieType;
use App\Form\SortieVilleType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class SortieController extends AbstractController
{

    public function Create(Request $request)
    {

       //instance d'une Sortie
        $sortie = new Sortie();

        //Création d'un formulaire de sortie
        $formSortie = $this->createForm(SortieType::class, $sortie);
//        $formVille = $this->createForm(SortieVilleType::class,$ville);

        $formSortie->handleRequest($request);
/

        //envoi du form a la page
       return $this->render('sortie/create.html.twig', array(
           //'listeVilleLieu' => $listVilleLieu,
           'formSortie' => $formSortie->createView(),
            //'formVille' => $formVille->createView()
       ));

    }

    public function Detail($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findOneById($id);

        $listParticipant = $sortieRepo->findListParticipant($id);

       $data = [];
        $i=0;
        foreach ($listParticipant[0] as $participants){
            $data[$i] = $participants;
            $i++;        }

        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n'existe pas !");
        }

        return $this->render('sortie/detail.html.twig', array(
            'participants' => $listParticipant[0],
           'sortie' => $sortie
        ));
    }

    public function Delete($id)
    {
        //recup entitymanager
        $em = $this->getDoctrine()->getManager();
        //recup repository
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        //find la sortie
        $sortie = $sortieRepo->find($id);
        //remove this sortie
        $this->$em->remove($sortie);
        //flush information
        $this->$em->flush();
        //return home
        return $this->render('default/index.html.twig');
    }

    public function Update($id, Request $request)
    {

        $sortie = new Sortie();
        $villeDeLaSortie = new Ville();
        $lieuDeLaSortie=new Lieu();

        //recup repository
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        //find la sortie

        $sortie = $sortieRepo->findAllInformtion($id);
        $lieu = $sortie[0]->getlieu();
        $ville = $sortie[0]->getlieu()->getville();
        $etat = $sortie[0]->getetat()->getId();

        //Recup de tout les états
        $etatRepo = $this->getDoctrine()->getRepository(EtatSortie::class);
        $etatlist = $etatRepo->findAll();
        $e = $etatRepo->findOneById($etat);

        //creation du formulaire
        $sortieForm = $this->createForm(SortieType::class,$sortie[0]);
        $sortieVilleForm = $this->createForm(SortieVilleType::class,$ville);
        $sortieLieuVilleForm = $this->createForm(SortieLieuVilleType::class, $lieu);

        $sortieForm->handleRequest($request);
        $sortieVilleForm->handleRequest($request);
        $sortieLieuVilleForm->handleRequest($request);

      //  dd( $sortieVilleForm);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()
        && $sortieVilleForm->isSubmitted() && $sortieVilleForm->isValid()
        && $sortieLieuVilleForm->isSubmitted() && $sortieLieuVilleForm->isValid()){

            $data = $request->request->get('sortie');

            //crée un message flash à afficher sur la prochaine page
            $this->addFlash('success', 'Votre sortie à été modifier !');

            if (isset($data['save']))
            {
                $sortie->setEtat($e);
                //recup entitymanager
                $em = $this->getDoctrine()->getManager();
                //on demande à Doctrine de sauvegarder notre instance
                $em->persist($sortie);
                $em->persist($ville);
                //on exécute les requêtes
                $em->flush();
                //redirige vers la page de détails de cette ajout
                return $this->redirectToRoute('sortie_detail',
                    ['id' => $sortie->getId()]
                );

            } elseif (isset($data['saveandpublished']))
            {
                $sortie->setEtat($e);
                //recup entitymanager
                $em = $this->getDoctrine()->getManager();
                //on demande à Doctrine de sauvegarder notre instance
                $em->persist($sortie);
                $em->persist($ville);
                //on exécute les requêtes
                $em->flush();
                //redirige vers la page de détails de cette ajout
                return $this->redirectToRoute('sortie_detail',
                    ['id' => $sortie->getId()]
                );
            }

            $sortie[0]->setId($id);
            $sortie[0]->setlieu($sortieForm->get('lieu')->getData());
            $sortie[0]->getlieu()->setville($sortieLieuVilleForm->get('nom')->getData());

            //recup entitymanager

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->persist($ville);
            $em->persist($lieu);
            //on exécute les requêtes
            $em->flush();

            //crée un message flash à afficher sur la prochaine page
            $this->addFlash('success', 'Votre sortie à été mise a jour !');

            //redirige vers la page de détails de cette ajout
            return $this->redirectToRoute('sortie_detail',
                ['id' => $sortie->getId()]
            );
        }

        //return home
        return $this->render('sortie/update.html.twig', array(
            'sortie' => $sortie,
            'formSortie' => $sortieForm->createView(),
            'formVille' => $sortieVilleForm->createView(),
            'formLieu' =>  $sortieLieuVilleForm->createView(),
            'id' => $id
        ));
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

    public function UpdateListVille($idSortie, $idVille){

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $villeRepos = $this->getDoctrine()->getRepository(Lieu::class);

        $sortie = $sortieRepo->findById($idSortie);
        $lieuList = $villeRepos->findAll($idVille);

        dd($lieuList);

        echo json_encode(array('listVille' => $lieuList));

    }

    public function UpdateListLieu($idVille){

    }






}