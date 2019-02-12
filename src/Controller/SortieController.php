<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 13:48
 */

namespace App\Controller;


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


}