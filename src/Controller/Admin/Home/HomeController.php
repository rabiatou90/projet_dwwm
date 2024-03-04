<?php

namespace App\Controller\Admin\Home;

use App\Repository\AgenceRepository;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/admin/home', name: 'admin_home_index', methods:['GET'])]
    public function index(
        AgenceRepository $agenceRepository, 
        UserRepository $userRepository,
        ContactRepository $contactRepository
    ): Response
    {
        return $this->render('pages/admin/home/index.html.twig',[
            "agences"  => $agenceRepository->findAll(),
            "user"     => $userRepository->findAll(),
            "contacts" => $contactRepository->findAll(),
        ]);
    }
}
