<?php

namespace App\Controller\Visitor\welcome;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class welcomeController extends AbstractController
{
    #[Route('/', name: 'visitor_welcome_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/welcome/index.html.twig');
    }
}
