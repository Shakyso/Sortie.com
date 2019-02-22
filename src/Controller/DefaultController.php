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

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultController extends AbstractController
{

    function Index(Request $request)
    {

        //création du formulaire de recherche
        //TODO faire le filtre sur les actions des organisateurs annuler publier

        $defaultData=['mon formulaire'=>'mes données'];
        $searchForm = $this->createFormBuilder($defaultData)
            ->setMethod('POST')
            ->add('site', EntityType::class, array(
                'multiple' => false,
                'label' => 'Les sites',
                'expanded' => false,
                'class' => Site::class,
                'choice_label' => 'nom'
            ))

            ->add('motCle', TextType::class)
            //TODO gérer l'affichage des dates agenda
/*
            ->add('startDateTime', DateType::class, [
                'date_label' => 'Starts On',
                'label' => 'Entre',
                'format'=>'dd-MM-yyyy',
                'widget'=>'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])

            ->add('endDateTime', DateTimeType::class, [
                'date_label' => 'Starts End',
                'label' => 'Et',
                'format'=>'dd-MM-yyyy',
                'widget'=>'single_text',
                'html5' => false,
                'help' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker'],
            ])
*/
            ->add('organisateur', CheckboxType::class, [
                'label'    => '',
                'required' => false,
            ])
            ->add('inscrite', CheckboxType::class, [
                'label'    => '',
                'required' => false,
            ])
            ->add('pasInscrite', CheckboxType::class, [
                'label'    => '',
                'required' => false,
            ])
            ->add('passees', CheckboxType::class, [
                'label'    => '',
                'required' => false,
            ])
            ->add('send', SubmitType::class)
            ->getForm()
            ;


        $searchForm->handleRequest($request);

        //est ceque le formulaire est soumis et récup des données saisies
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            //echo ('je suis dans le if traitement des donénes du formulaire');
            // récupére rles paramètres saisie dans le ofrmulaires
            $data = $searchForm->getData();
            //TODO faire la requete en fonction de tout les paramètres
//            var_dump($data['organisateur']);
//            var_dump($nomSite);
//            var_dump($data['motCle']);
//            var_dump($data['pasInscrite']);
//            var_dump($data['inscrite']);
//            var_dump($data['passees']);
            $site=new Site;
            $site=$data['site'];
            $nomSite=$site->getNom();
            $idSite=$site->getId();
            $motCle=$data['motCle'];
            $organisateur=$data['organisateur'];
            $pasInscrite=$data['pasInscrite'];
            $inscrite=$data['inscrite'];
            $passees=$data['passees'];
            /////// faire les requetes en base

            ////////////////////////////////////////////////////////////////////////
            //recherche des sorties en fonction des sites
            //////////////////////////////////////////////////////////////////////////
            if ($idSite){
                var_dump('je susi dans la requete pour les sites');
                $siteRepo=$this->getDoctrine()->getRepository(Site::class);
                $site=$siteRepo->find($idSite);
                //$siteRepo->persist($site);
                var_dump($site);
              //  dd();
            }
            $listeDesSorties =$sortieRepository->selectListSortie($site);
            ///////////////////////fin recherche par site///////////////////////////////////
            ///
            return $this->redirectToRoute('index_select');

        }

        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $listeDesSorties = $sortieRepository->findListSortieUser();

        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());

        } else {
            $maliste = "";
            //Find la liste de Sortie
        //    $listeDesSorties = $sortieRepository->findListSortie();

        }
        $arrayParticipant = array();
        foreach($listeDesSorties as $sortie){
            $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
            $arrayParticipant[$sortie->getId()] = $nombreParticipant;
        }
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'searchAccueilForm'=>$searchForm->createView(),
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));
    }

    function selectIndex(Request $request)
    {
echo ('je suis dans ma fonction selectIndex');


//        if ($searchForm->isSubmitted() && $searchForm->isValid()){
    //        echo ('je suis dans mon if');
            var_dump($_POST);
            dd();
      //  }
        /*
        //envoi du form à la page
        return $this->render('default/index.html.twig', array(
            'searchAccueilForm'=>$searchForm->createView(),
            //'listeDesSorties' => $listeDesSorties,
            //'nombreParticipant' => $arrayParticipant,
            //'maliste' => $maliste
        ));


        /*
        $user = $this->getUser();
        //Recupérer le repository des Sorties
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);

        $listeDesSorties = $sortieRepository->findListSortieUser();
        if(!is_null($user)){
            $maliste = $sortieRepository->findParticipation($user->getId());

        } else {
            $maliste = "";
            //Find la liste de Sortie
            //    $listeDesSorties = $sortieRepository->findListSortie();
        }
        $arrayParticipant = array();
        foreach($listeDesSorties as $sortie){
            $nombreParticipant = $sortieRepository->findNbParticipant($sortie->getId());
            $arrayParticipant[$sortie->getId()] = $nombreParticipant;
        }
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));


*/
        //envoie de la liste a la page d'accueille
        return $this->render('default/index.html.twig', array(
            'listeDesSorties' => $listeDesSorties,
            'nombreParticipant' => $arrayParticipant,
            'maliste' => $maliste
        ));
    }

    //création du formulaire de recherche
    function createFormSearch (FormBuilderInterface $builder)
    {

    }

}