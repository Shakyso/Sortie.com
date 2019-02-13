<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:18
 */

namespace App\Controller;


use App\Entity\Site;
use App\Entity\Ville;
use App\Form\VilleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
        $siteForm=$this->createForm(VilleType::class, $ville);
        $siteForm->handleRequest($request);
        if ($siteForm->isSubmitted() && $siteForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            return $this->redirectToRoute('ville_list');
        }
        return $this->render(' admin/ville/create.html.twig',
            [
                "siteForm" => $siteForm->createView(),
            ]);


    }
    public function List()
    {
    }

    public function Search()
    {
    }

    public function Update($id)
    {
    }

    public function Delete($id)
    {
    }



}