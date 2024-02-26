<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\ResetPasswordRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/user/list', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('pages/admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id<\d+>}/edit/roles', name: 'admin_user_edit', methods: ['GET', 'PUT'])]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        // Créez un nouveau formulaire avec seulement le champ 'roles'
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT', 'validation_groups' => ['roles']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour des rôles
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Les rôles de {$user->getPrenom()} {$user->getNom()} ont été modifiés avec succès.");

            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('pages/admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/{id<\d+>}/delete', name: 'admin_user_delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('csrf_token'))) {
            // Récupère les demandes de réinitialisation de mot de passe associées à l'utilisateur
            $resetPasswordRequests = $em->getRepository(ResetPasswordRequest::class)->findBy(['user' => $user]);
            // Récupère toutes les entités liées, à l'exception de l'entité Agence
            $relatedEntities = $user->getRelatedEntitiesExceptAgence();

            // Supprime les demandes de réinitialisation de mot de passe associées
            foreach ($resetPasswordRequests as $resetPasswordRequest) {
                $em->remove($resetPasswordRequest);
            }

            // Supprime manuellement toutes les entités liées
            foreach ($relatedEntities as $relatedEntity) {
                $em->remove($relatedEntity);
            }

            $this->addFlash('success', "{$user->getPrenom()} {$user->getNom()} a été supprimé!");

            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
