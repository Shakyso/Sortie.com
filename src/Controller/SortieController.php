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

use App\Entity\User;

use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;

use App\Form\SortieVilleType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SortieController extends AbstractController
{

    public function Create(Request $request)
    {
        //instance d'une Sortie
        $sortie = new Sortie();
        $ville =new Ville();

        //Création d'un formulaire de sortie
        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formVille = $this->createForm(SortieVilleType::class,$ville);

        $formSortie->handleRequest($request);
        $formVille->handleRequest($request);

        //Recup de tout les états
        $etatRepo = $this->getDoctrine()->getRepository(EtatSortie::class);
        $etatlist = $etatRepo->findAll();

        $lieuRepo = $this->getDoctrine()->getRepository(Lieu::class);
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);

        $listVille = $villeRepo->findAllVille();
        $listVilleLieu = $lieuRepo->findAllLieuVille();


        foreach($etatlist as $e) {
            //test du formulaire
            if ($formSortie->isSubmitted() && $formSortie->isValid() && $formVille->isSubmitted() && $formVille->isValid()) {
                //recup des éléments $request du formulaire
                $data = $request->request->get('sortie');
                //crée un message flash à afficher sur la prochaine page
                $this->addFlash('success', 'Votre sortie à été ajoutée !');

                if (isset($data['save']) && $e == "Créée")
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

                } elseif (isset($data['saveandpublished']) && $e == "Ouverte")
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
            }
        }

        //envoi du form a la page
       return $this->render('sortie/create.html.twig', array(
           'listeVilleLieu' => $listVilleLieu,
           'formSortie' => $formSortie->createView(),
            'formVille' => $formVille->createView()
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

        //Sullivan
        //$sortie = $sortieRepo->findAllInformtion($id);
        $sortie=$sortieRepo->find($id);

        $lieuDeLaSortie=$sortie->getLieu();
        $villeDeLaSortie=$lieuDeLaSortie->getVille();
        //$ville = $sortie[0]->getlieu()->getville();

        //dd($ville);
        //creation du formulaire de sortie
        $sortieForm = $this->createForm(SortieType::class,$sortie);
        //$sortieVilleForm = $this->createForm(SortieVilleType::class, $ville);
        // dd($sortieVilleForm);
        $sortieForm->handleRequest($request);

        //création du formulaire du lieu
        $lieuForm = $this->createForm(LieuType::class,$lieuDeLaSortie);
        //$sortieVilleForm = $this->createForm(SortieVilleType::class, $lieuDeLaSortie);
        $lieuForm->handleRequest($request);

      //  dd( $sortie[0]->getlieu()->setVille($ville));
        if($sortieForm->isSubmitted() && $sortieForm->isValid() && $lieuForm->isSubmitted() && $lieuForm->isValid()){

            $sortie->setId($id);
            //$sortie[0]->setlieu($sortieForm->get('lieu')->getData());
            //$sortie[0]->getlieu()->setVille($ville);
            //recup entitymanager

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->persist($villeDeLaSortie);
            $em->persist($lieuDeLaSortie);
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
            //sullivan
            //'formVille' =>$sortieVilleForm->createView(),
            'formLieu' =>$lieuForm->createView(),

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






}