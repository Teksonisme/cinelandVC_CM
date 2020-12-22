<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CinelandController extends AbstractController
{
    
    public function menu(Session $session)
    {
        return $this->render('menu.html.twig');
    }

    public function pageNotFound(Session $session)
    {
        $session->getFlashBag()
            ->add('pageNotFound', 'Il semblerait que vous ayez accéder à une page inexistante.');
        return $this->redirectToRoute('menu');
    }
}
