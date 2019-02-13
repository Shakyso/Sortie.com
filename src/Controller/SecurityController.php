<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:19
 */
namespace App\Controller;


use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Site;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;

class SecurityController  extends AbstractController{



    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
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

        return $this->redirectToRoute('/Sortie.com/public/');
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

    public function Account($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)

    {

        $errors = "";

        // Récupération du User en base
        $em =  $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->findOneById($id);


        // Création du formualaire
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        // Vérififcation du formulaire
        if($form->isSubmitted()) {

            $errors = $form->getErrors(true, false);



            echo 'je passe dans le submit';

            $user = $form->getData();
            $validator = Validation::createValidator();
            $errors = $validator->validate($user);


            foreach ($form as $fieldName => $formField) {
                // each field has an array of errors
                $errors[$fieldName] = $formField->getErrors();
                var_dump($errors[$fieldName] = $formField->getErrors());
            }




            if($form->isValid()){
                echo 'formulair evalide';
                // Update des données
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }else{
                echo 'fiormulaire non valide';
            }


        }

        return $this->render('default/account.html.twig', [
            'form' => $form->createView(),
            //'errors' => $errors,
            //'formPassword' => $formPassword->createView(),

        ]);

    }




}