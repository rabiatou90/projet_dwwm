<?php

namespace App\Controller\Agent\welcome;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class welcomeController extends AbstractController
{
    #[Route('/', name: 'agent_welcome_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/agent/welcome/index.html.twig');
    }
}
