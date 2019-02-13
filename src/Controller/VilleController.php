<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:18
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VilleController  extends AbstractController
{
    public function CreateVille(Request $request)
    {
        /*
        if (!$this->isGranted("ROLE_ADMIN")){
            throw $this->createAccessDeniedException("dégage");
        }
*/

        $ville=new Ville();
        $villeForm=$this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('site_list');
        }
        return $this->render('admin/site/create.html.twig',
            [
                "villeForm" => $villeForm->createView(),
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