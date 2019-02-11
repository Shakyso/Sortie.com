<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 11/02/2019
 * Time: 11:41
 */

namespace App\Controller;


class DefaultController
{

    function index()
    {
        return $this->render('default/index.html.twig');
    }

}