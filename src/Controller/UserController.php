<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    public function Account($id){

        $user = $this->getDoctrine()->getRepository(User::class)->findOneById($id);
        return $this->render('user/public.html.twig', ['user' => $user]);


    }
}
