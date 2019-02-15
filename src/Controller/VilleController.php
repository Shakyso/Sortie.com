<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:18
 */

namespace App\Controller;


use App\Entity\Ville;
use App\Form\VilleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VilleController  extends AbstractController
{

    public function Search()
    {
    }

    public function List(Request $request)
    {
        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);
        $villesList=$villeRepository->findAll();

        //formulaire de saisie d'une nouvelle ville
        $ville=new Ville();
        $villeForm=$this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('ville_list');
        }
        return $this->render('admin/create.html.twig', [
            "villesList" => $villesList,
            "villeForm" => $villeForm->createView(),
            ]);
    }


    public function Update($id, Request $request)
    {
        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);
        $villesList=$villeRepository->findAll();

        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);
        $villeId=$villeRepository->find($id);
        var_dump($id);

        //TODO gérer la modofcation des données en javascript
        if($id!=null){

            $villeUpdate = $this->createForm(VilleType::class, $villeId);

            if ($villeUpdate->isSubmitted() && $villeUpdate->isValid()){
                $em = $this->getDoctrine()->getManager();
                $villeId->$em->setNom();
                $villeId->$em->setCodePostal();
                $villeId->$em->setId($id);
                $em->flush();
                return $this->redirectToRoute('ville_list');
            }

            //return en json_encode('string');git status
        }
        //TODO si l'id n'existe pas
        $ville=new Ville();
        $villeForm=$this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('ville_list');
        }


        return $this->render('admin/create.html.twig', [
            "villesList" => $villesList,
            'formUpdate' => $villeUpdate->createView(),
            "villeForm" => $villeForm->createView(),
        ]);
    }

    public function Delete($id)
    {
        //TODO gestion de l'erreur et des contraintes => il y des sorties associé donc on ne peut pas supprimer la ville
        $villeRepository=$this->getDoctrine()->getRepository(Ville::class);
        $villeId=$villeRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($villeId);
        $em->flush();
            return $this->redirectToRoute('ville_list');
        }


}