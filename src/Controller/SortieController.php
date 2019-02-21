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
use App\Entity\Site;
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
        $sortie = new Sortie();
        $lieu = new Lieu();
        $user = new User();



        //Création d'un formulaire de sortie
        $sortieForm = $this->createForm(SortieType::class,$sortie);
        $sortieLieuVilleForm = $this->createForm(SortieLieuVilleType::class, $lieu);

        $sortieForm->handleRequest($request);
        $sortieLieuVilleForm->handleRequest($request);

        //Recup de tout les états
        $etatRepo = $this->getDoctrine()->getRepository(EtatSortie::class);
        $etatlist = $etatRepo->findAll();

        foreach($etatlist as $e) {

            if ($sortieForm->isSubmitted() && $sortieLieuVilleForm->isSubmitted()) {
                $sortie = new Sortie();
                $user = new User();
                $site = new Site();
                $site = $this->getUser()->getSite();
                $user = $this->getUser()->getId();
                $sortie = $sortieForm->getData();
                var_dump($user);

                if ($sortieForm->isValid() && $sortieLieuVilleForm->isValid()){

                    //recup des éléments $request du formulaire
                    $data = $request->request->get('sortie');
                    //crée un message flash à afficher sur la prochaine page
                    $this->addFlash('success', 'Votre sortie à été ajoutée !');

                    if (isset($data['save']) && $e == "Créée") {
                        $sortie->setEtat($e);
                        $sortie->setOrganisateur($user);
                        $sortie->setSiteOrganisateur($site);
                        //recup entitymanager
                        $em = $this->getDoctrine()->getManager();
                        //on demande à Doctrine de sauvegarder notre instance
                        $em->persist($sortie);

                        //on exécute les requêtes
                        $em->flush();
                        //redirige vers la page de détails de cette ajout
                        return $this->redirectToRoute('sortie_detail',
                            ['id' => $sortie->getId()]
                        );
                    }
                    if (isset($data['save and published']) && $e == "Ouverte") {
                        $sortie->setEtat($e);
                        $sortie->setOrganisateur($user);
                        $sortie->setSiteOrganisateur($site);
                        //recup entitymanager
                        $em = $this->getDoctrine()->getManager();
                        //on demande à Doctrine de sauvegarder notre instance
                        $em->persist($sortie);

                        //on exécute les requêtes
                        $em->flush();
                        //redirige vers la page de détails de cette ajout
                        return $this->redirectToRoute('sortie_detail',
                            ['id' => $sortie->getId()]
                        );
                    }
                }


            }
        }

        //envoi du form a la page
       return $this->render('sortie/create.html.twig', array(
           'formSortie' => $sortieForm->createView(),
           'formLieu' =>  $sortieLieuVilleForm->createView(),
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
        $lieuDeLaSortie=new Lieu();

        //recup repository
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $lieuRepo = $this->getDoctrine()->getRepository(Lieu::class);
        //find la sortie

        $sortie = $sortieRepo->findAllInformtion($id);
        $ville = $sortie[0]->getlieu()->getville();

        $idVille= $ville->getId();
        $lieu = $lieuRepo->findAllLieuParVille($idVille);
        $etat = $sortie[0]->getetat()->getId();

        //Recup de tout les états
        $etatRepo = $this->getDoctrine()->getRepository(EtatSortie::class);
        $etatlist = $etatRepo->findAll();
        $e = $etatRepo->findOneById($etat);

        //creation du formulaire
        $sortieForm = $this->createForm(SortieType::class,$sortie[0]);
        $sortieVilleForm = $this->createForm(SortieVilleType::class,$ville);
        $sortieLieuVilleForm = $this->createForm(SortieLieuVilleType::class, $lieu[0]);

        $sortieForm->handleRequest($request);
        $sortieVilleForm->handleRequest($request);
        $sortieLieuVilleForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieLieuVilleForm->isSubmitted()) {
            $sortie = new Sortie();
            $user = new User();
            $site = new Site();
            $site = $this->getUser()->getSite();
            $user = $this->getUser()->getId();
            $sortie = $sortieForm->getData();


            if ($sortieForm->isValid() && $sortieLieuVilleForm->isValid()){
                //recup des éléments $request du formulaire
                $data = $request->request->get('sortie');

                //crée un message flash à afficher sur la prochaine page
                $this->addFlash('success', 'Votre sortie à été ajoutée !');

                if (isset($data['save']) && $e == "Créée") {
                    $sortie->setEtat($e);
                    $sortie->setOrganisateur($user);
                    $sortie->setSiteOrganisateur($site);
                    //recup entitymanager
                    $em = $this->getDoctrine()->getManager();
                    //on demande à Doctrine de sauvegarder notre instance
                    $em->persist($sortie);

                    //on exécute les requêtes
                    $em->flush();
                    //redirige vers la page de détails de cette ajout
                    return $this->redirectToRoute('sortie_detail',
                        ['id' => $sortie->getId()]
                    );

                }
                if (isset($data['save and published']) && $e == "Ouverte") {
                    $sortie->setEtat($e);
                    $sortie->setOrganisateur($user);
                    $sortie->setSiteOrganisateur($site);

                    //recup entitymanager
                    $em = $this->getDoctrine()->getManager();
                    //on demande à Doctrine de sauvegarder notre instance
                    $em->persist($sortie);

                    //on exécute les requêtes
                    $em->flush();
                    //redirige vers la page de détails de cette ajout
                    return $this->redirectToRoute('sortie_detail',
                        ['id' => $sortie->getId()]
                    );
                }
            }
        }
        return $this->render('sortie/update.html.twig', array(
            'sortie' => $sortie,
            'formSortie' => $sortieForm->createView(),
            'formVille'  => $sortieVilleForm->createView(),
            'formLieu'   =>  $sortieLieuVilleForm->createView(),
            'id' => $id
        ));
    }


    public function inscrireSortie (int $idParticipant, int $idSortie) {
        $sortie = new Sortie();
        $participant = new User();
        //associer les objects sortie et user entre eux


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

    public function UpdateListLieu(){

        $villeRipo = $this->getDoctrine()->getRepository(Ville::class);

        $lieux = $villeRipo->findAllVilleLieu($_POST['idVille']);

        $listeLieu = json_encode(array('listeLieu' => $lieux));

       return new Response($listeLieu);


    }






}