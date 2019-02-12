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
use App\Entity\Site;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController  extends AbstractController{





    public function AccountAction($id)
    {
    }

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
        $message = "Vous êtes bien deconnecté";
        return $this->redirectToRoute('/Sortie.com/public/', $message);
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {


        $em =  $this->getDoctrine()->getManager();
        $siteRepo = $em->getRepository(Site::class);
        $site = $siteRepo->findOneById(1);


        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setUsername($request->request->get('nomComplet'));
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $user->setSite($site);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('security/register.html.twig');
    }

}