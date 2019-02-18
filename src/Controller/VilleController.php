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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

//$encoders = new JsonEncoder();
//$normalizers = new ObjectNormalizer();
//$serializer =new Serializer ($normalizers, $encoders);

class VilleController   extends AbstractController
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
    public function Update()
    {
      //  echo 'echo';
        // récupère les données du POST

        // fonctionne
        $nvCode = $_POST ['nvCode'];
        $nvNomVille = $_POST['nvNomVille'];
        $idVille = $_POST['idVille'];

        if($idVille!=null) {
            $villeRepository = $this->getDoctrine()->getRepository(Ville::class);
            $villeAMAJ = $villeRepository->find($idVille);
            $em = $this->getDoctrine()->getManager();
            $villeAMAJ->setNom($nvNomVille);
            $villeAMAJ->setCodePostal($nvCode);
            $em->flush();
        }
        //var_dump($villeAMAJ);
        //récupére le donnée sd'une ville modifier

        //$villeModif=$serializer->serialize($villeAMAJ, 'jason');
        echo($villeModif);
      return new Response ($villeModif);
      //  return new Response ('test');
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