<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{


    /**
     * Page d'erreur pour les problèmes d'accès
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return Response
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = '<p>je suis une page d\'erreur</p>';
        return new Response($content, 403);

        //return $this->render('je.html.twig');
    }
}