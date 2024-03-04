<?php

namespace App\Controller\Admin\Agence;

use App\Entity\Agence;
use App\Form\AgenceFormType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgenceController extends AbstractController
{
    #[Route('/admin/agence/list', name: 'admin_agence_index', methods:['GET'])]
    public function index(AgenceRepository $agenceRepository): Response
    {

        $agences = $agenceRepository->findAll();

        return $this->render('pages/admin/agence/index.html.twig', [
            "agences" => $agences
        ]);
    }

    #[Route('/admin/agence/create', name: 'admin_agence_create', methods:['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {
        $agence = new Agence();

        $form = $this->createForm(AgenceFormType::class, $agence);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $em->persist($agence);
            $em->flush();

            $this->addFlash("success", "L'agence a été ajoutée.");

            return $this->redirectToRoute('admin_agence_index');
        }

        return $this->render('pages/admin/agence/create.html.twig', [
            "form" =>$form->createView()
        ]);
    }

    #[Route('/admin/agence/{id}/edit', name: 'admin_agence_edit', methods:['GET', 'PUT'])]
    public function edit(Agence $agence, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(AgenceFormType::class, $agence, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $em->persist($agence);
            $em->flush();

            $this->addFlash('success', "L'agence a été modifiée");

            return $this->redirectToRoute("admin_agence_index");
        }

        return $this->render("pages/admin/agence/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }
    
    #[Route('/admin/agence/delete/{id}', name: 'admin_agence_delete', methods: ['DELETE'])]
    public function delete(Agence $agence, EntityManagerInterface $em): Response
    {
        $em->remove($agence);
        $em->flush();

        $this->addFlash('success', "L'agence a été supprimée avec succès.");

        return $this->redirectToRoute('admin_agence_index');
    }

    }
    

