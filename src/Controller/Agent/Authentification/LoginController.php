<?php

namespace App\Controller\Agent\Authentification;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class LoginController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/register', name: 'agent_authentification_register')]
    public function register(
        Request $request,
        AuthorizationCheckerInterface $authorizationChecker,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        // Vérification si l'utilisateur en cours est un administrateur
        if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seuls les administrateurs peuvent accéder à cette fonctionnalité.');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Génération du mot de passe aléatoire
            $randomPassword = $this->generateRandomPassword();

            // Hachage du mot de passe
            $hashedPasswordHashed = $userPasswordHasher->hashPassword($user, $randomPassword);
            $user->setPassword($hashedPasswordHashed);

            try {
                // Enregistrement de l'utilisateur en base de données
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                // Gérer l'erreur en cas de violation de contrainte d'unicité (doublon d'e-mail)
                $this->addFlash('error', 'L\'utilisateur avec cette adresse e-mail existe déjà.');
                return $this->redirectToRoute('agent_authentification_register');
            }

            // Affichage du mot de passe généré
            $this->addFlash('random_password', $randomPassword);
            dd($randomPassword);
            // Redirection vers la page d'accueil après l'inscription
            return $this->redirectToRoute('agent_welcome_index');
            
        }

        // Rendre la page d'inscription
        return $this->render('pages/agent/authentification/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateRandomPassword(int $length = 8): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $charactersLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        return $password;
    }


    #[Route(path: '/login', name: 'agent_authentification_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('agent_welcome_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/agent/authentification/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
