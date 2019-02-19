<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:19
 */
namespace App\Controller;


use App\Classes\Validator;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;


class SecurityController  extends AbstractController{


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        var_dump($this->isGranted('IS_AUTHENTICATED_FULLY'));
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

        return $this->redirectToRoute($this->container->get('router')->getContext()->getBaseUrl());
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setUsername($request->request->get('nomComplet'));
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            //$user->setSite($site);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute('index');
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * @param $id
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function Account($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $messagePassword = null;


        // Récupération du User en base
        $em =  $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->findOneById($id);



        // pour ajouter  ->
        //$user->setRoles('ROLE_ADMIN');




        // Si une photo existe
        $userPhoto = $user->getPhoto();

        // Création du formulaire
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);



        // Validation des champs / Contruction des erreures
        if($form->isSubmitted()) {

            // Récupération des données du formulaire
            $user = $form->getData();

            if($form->isValid()){

                echo 'je suis valide';

                $file = $user->getPhoto();


                if($user->getPhoto()){
                    $fileName =  md5(uniqid()).'.'.$file->guessExtension();

                    try {
                        $directory = $this->getParameter('photos_directory');
                        $file->move($directory, $fileName);

                    }catch (FileException $e){
                        $e->getMessage();
                    }
                    $user->setPhoto($fileName);
                }elseif($userPhoto){
                    $user->setPhoto($userPhoto);
                }


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

            }else{
                $user->setPhoto($userPhoto);
            }
        }


        // Vérification formulaire mot de passe
        if(isset($_POST['submitPassword']) && $_POST['submitPassword'] === 'Modifier'){
            // Nettoyage du $_POST
            $post = array_map('trim', array_map('strip_tags', $_POST));
            // Vérification du mot de passe
            $passwordVerified = $this->verifPassword($post['password'], $post['confirmPassword']);
            // Changement du mot de passe
            $messagePassword = $this->changePassword($user, $passwordVerified, $passwordEncoder, $em);
        }


        return $this->render('default/account.html.twig', [

            'form' => $form->createView(),
            'messagePassword' => $messagePassword,
            'userPhoto' => $user->getPhoto(),

        ]);
    }



    /**
     * @param $password
     * @param $confirmPassword
     * @return String | null
     */
    public function verifPassword($password, $confirmPassword){
        if($password === $confirmPassword){
            return $password;
        }else{
            return null;
        }
    }


    /**
     * @param User $user
     * @param $verifiedPassword
     * @param $passwordEncoder
     * @param $em
     * @return string
     */
    public function changePassword(User $user, $verifiedPassword, $passwordEncoder, $em): String{

        if($verifiedPassword != null){

            $newEncodedPassword = $passwordEncoder->encodePassword($user, $verifiedPassword);
            $user->setPassword($newEncodedPassword);

            $em->persist($user);
            $em->flush();

            $message = 'success';
        }else{
            $message = 'error';
        }

        return $message;
    }




}