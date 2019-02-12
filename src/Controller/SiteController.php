<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 12:18
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController  extends AbstractController
{


    public function ListAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function SearchAction()
    {
    }

    public function UpdateAction($id)
    {
    }

    public function DeleteAction($id)
    {
    }



}