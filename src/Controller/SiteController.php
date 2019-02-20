<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:18
 */

namespace App\Controller;


use App\Entity\Site;

use App\Form\SiteType;
use App\Form\VilleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController  extends AbstractController
{

    public function CreateSite(Request $request)
    {
        /*
        if (!$this->isGranted("ROLE_ADMIN")){
            throw $this->createAccessDeniedException("dégage");
        }
*/

        $site=new Site();
        $siteForm=$this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            return $this->redirectToRoute('site_list');
        }
        return $this->render('admin/createSite.html.twig',
            [
                "siteForm" => $siteForm->createView(),
            ]);


    }
    public function List(Request $request)
    {
        $siteRepository=$this->getDoctrine()->getRepository(Site::class);
        $sitesList=$siteRepository->findAll();

        //formulaire de saisie d'une nouvelle ville
        $site = new Site();
        $siteForm = $this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            return $this->redirectToRoute('site_list');
        }

        //var_dump($siteForm->createView());

        return $this->render('admin/createSite.html.twig', [
            "sitesList" => $sitesList,
            "siteForm" => $siteForm->createView(),
        ]);
    }

    public function Search()
    {
    }

    public function Update()
    {
        $nvNomSite = $_POST['nvNomSite'];
        $idSite = $_POST['idSite'];

        if($idSite!=null) {
            $siteRepository = $this->getDoctrine()->getRepository(Site::class);
            $siteAMAJ = $siteRepository->find($idSite);
            $em = $this->getDoctrine()->getManager();
            $siteAMAJ->setNom($nvNomSite);
            $em->flush();
        }
        //  var_dump($villeAMAJ);
        //récupére le donnée sd'une ville modifier
        $tab = array(
            "idSite"=>$idSite,
            "nomSite"=>$nvNomSite,
        );
        $siteModif=json_encode($tab);
        return new Response($siteModif);
    }

    public function Delete($id)
    {
        //TODO gestion de l'erreur et des contraintes => il y des sorties associé donc on ne peut pas supprimer la ville
        $siteRepository=$this->getDoctrine()->getRepository(Site::class);
        $siteId=$siteRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($siteId);
        $em->flush();
        return $this->redirectToRoute('site_list');
    }



}