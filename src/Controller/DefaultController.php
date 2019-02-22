<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 11:41
 */
namespace App\Controller;


use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;

//use Doctrine\DBAL\Types\DateTimeType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SortieRepository;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultController extends AbstractController
{

    function Index(Request $request)
    {



        $site = new Site;
        $organizer = new Boolean();
        $signedOn = new Boolean();
        $notSignedOn = new Boolean();
        $pastEvent = new Boolean();
                    $site = null;
        $organizer = false;
        $signedOn = false;
        $notSignedOn = false;
        $pastEvent = false;
        $searchBar = null;
        /*$dateStart = new DateTimeType();
        $dateEnd= new DateTimeType();
        $dateStart = null;
        $dateEnd = null;*/

            $defaultData = ['mon formulaire' => 'mes données'];
            $searchForm = $this->createFormBuilder($defaultData)
                ->setMethod('POST')
                ->add('site', EntityType::class, array(
                    'multiple' => false,
                    'label' => 'Les sites',
                    'expanded' => false,
                    'class' => Site::class,
                    'choice_label' => 'nom',
                    'required'=>false,
                    'empty_data'=>null,
                ))

                ->add('motCle', TextType::class, array (
                    'required'=>false,
                ))

                ->add('organisateur', CheckboxType::class, [
                    'label' => '',
                    'required' => false,
                ])
                ->add('inscrite', CheckboxType::class, [
                    'label' => '',
                    'required' => false,
                ])
                ->add('pasInscrite', CheckboxType::class, [
                    'label' => '',
                    'required' => false,
                ])
                ->add('passees', CheckboxType::class, [
                    'label' => '',
                    'required' => false,
                ])
                ->add('send', SubmitType::class)
                ->getForm();
                //var_dump($request);
            $searchForm->handleRequest($request);
            //est ceque le formulaire est soumis et récup des données saisies
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                //echo ('je suis dans le if traitement des donénes du formulaire');
                // récupére rles paramètres saisie dans le ofrmulaires
                $data = $searchForm->getData();
                $site = $data['site'];
                //dd($site);
                //$nomSite=$site->getNom();
                //$idSite=$site->getId();
                $searchBar = $data['motCle'];
                $organizer = $data['organisateur'];
                $notSignedOn = $data['pasInscrite'];
                $signedOn = $data['inscrite'];
                $pastEvent = $data['passees'];
                /////// faire les requetes en base
            }


            ////////////////////////////////////
            /////// FIN CREATION DU FORMULAIRE DE RECHERCHE
            /////////////////////////////////////////////////////////

            $user = $this->getUser();



            //Recupérer le repository des Sorties
            $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
            $listeDesSorties = $sortieRepository->findListSortieUser();


            $arrayParticipant = array();
            foreach ($listeDesSorties as $sortie) {
                $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
                $arrayParticipant[$sortie->getId()] = $nombreParticipant;
            }


        //ma liste de participation ou je suis inscrite
        if (!is_null($user)) {
            $malisteParticipation = $sortieRepository->findParticipation($user->getId());
            //   $idUserConnecte= $user->getId();
            //   $nomUserConnecte= $user->getUsername();
            // var_dump($nomUserConnecte,$idUserConnecte);

        } else {
            $malisteParticipation = "";
            //Find la liste de Sortie
            //    $listeDesSorties = $sortieRepository->findListSortie();

        }
        ////////////////////////////////////////////////////////////////////////
        //recherche des sorties en fonction des sites
        //////////////////////////////////////////////////////////////////////////
        if ($site!=null or $searchBar!= null or $organizer or $signedOn or $notSignedOn or $pastEvent){
            $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
            $listeDesSorties =$sortieRepository->selectListSortie($user, $site, $searchBar, $organizer, $signedOn, $notSignedOn, $pastEvent);
        }

        //envoie de la liste a la page d'accueille
            return $this->render('default/index.html.twig', array(
                'searchAccueilForm' => $searchForm->createView(),
                'listeDesSorties' => $listeDesSorties,
                'nombreParticipant' => $arrayParticipant,
                'maliste' => $malisteParticipation
            ));
        }


}