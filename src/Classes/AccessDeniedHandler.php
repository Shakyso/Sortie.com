<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
public function handle(Request $request, AccessDeniedException $accessDeniedException)
{
    $content = '<p>je suis une page d\'erreur</p>';
    return new Response($content, 403);
    }
}