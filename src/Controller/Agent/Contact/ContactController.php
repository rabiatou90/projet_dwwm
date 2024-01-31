<?php

namespace App\Controller\Agent\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'agent_contact_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/agent/contact/index.html.twig');
    }
}
