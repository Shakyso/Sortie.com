<?php
/**
 * Created by PhpStorm.
 * User: tdelmas2017
 * Date: 20/02/2019
 * Time: 09:22
 */

namespace App\Classes;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotGranted extends AbstractController
{


    public function IsNotGranted(){
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->render('security/register.html.twig');

        }
    }

}