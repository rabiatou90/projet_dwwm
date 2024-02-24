<?php

namespace App\Controller\Visitor\Transfert;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransfertController extends AbstractController
{
    #[Route('/transfert', name: 'agent_transfert_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/agent/transfert/index.html.twig');
    }
}
