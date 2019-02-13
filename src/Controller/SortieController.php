<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 13:48
 */

namespace App\Controller;


use App\Entity\EtatSortie;
use App\Entity\Sortie;
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
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties
        ));

    }

    public function Create(Request $request)
    {
        //instance d'une Sortie
        $sortie = new Sortie();
        //Création d'un formulaire de sortie
        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formSortie->handleRequest($request);

        //Recup de tout les états
        $etatRepo = $this->getDoctrine()->getRepository(EtatSortie::class);
        $etatlist = $etatRepo->findAll();

        foreach($etatlist as $e) {
            var_dump($e);

            //test du formulaire
            if ($formSortie->isSubmitted() && $formSortie->isValid()) {
                //recup des éléments $request du formulaire
                $data = $request->request->get('sortie');
                //crée un message flash à afficher sur la prochaine page
                $this->addFlash('success', 'Votre sortie à été ajoutée !');

                if (isset($data['save']) && $e == "Créée") {
                    $sortie->setEtat($e);
                    //recup entitymanager
                    $em = $this->getDoctrine()->getManager();
                    //on demande à Doctrine de sauvegarder notre instance
                    $em->persist($sortie);
                    //on exécute les requêtes
                    //$em->flush();
                    //redirige vers la page de détails de cette ajout
                    return $this->redirectToRoute('sortie_detail',
                        ['id' => $sortie->getId()]
                    );
                } elseif (isset($data['saveandpublished']) && $e == "Ouverte") {
                    $sortie->setEtat($e);
                    //recup entitymanager
                    $em = $this->getDoctrine()->getManager();
                    //on demande à Doctrine de sauvegarder notre instance
                    $em->persist($sortie);
                    //on exécute les requêtes
                    //$em->flush();
                    return $this->redirectToRoute('sortie_detail_public',
                        ['id' => $sortie->getId()]
                    );
                }
            }
        }

        //envoi du form a la page
       return $this->render('sortie/create.html.twig', array(
           'formSortie' => $formSortie->createView()
       ));

    }

    public function Detail($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findOneById($id);

        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n'existe pas !");
        }

        return $this->render('sortie/detail.html.twig', array(
           'sortie' => $sortie
        ));


    }
    public function Detail_public($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findOneById($id);

        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n'existe pas !");
        }

        return $this->render('sortie/detail.html.twig', array(
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

        //recup repository
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        //find la sortie
        $sortie = $sortieRepo->find($id);

        //creation du formulaire
        $sortieForm = $this->createForm(SortieType::class,$sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            //recup entitymanager
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
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
        return $this->render('sortie/detail.html.twig', array(
            'id' => $id
        ));
    }




}