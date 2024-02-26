<?php

namespace App\Controller\Transaction;

use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
    #[Route('admin/transaction/list', name: 'admin_transaction_index', methods:['GET'])]
    public function indexAdmin(): Response
    { 
        return $this->render('pages/admin/transaction/index.html.twig');
    }

    #[Route('admin/transaction/create', name: 'admin_transaction_create', methods:['GET', 'POST'])]
    public function createAdmin(Request $request, EntityManagerInterface $em): Response 
    { 
        $form = $this->createForm(TransactionType::class);
        $errors = [];

        try {
            // Traitement du formulaire principal
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {

                // Traitement du formulaire
                $clientData = $form->get('client')->getData();
                $destinataireData = $form->get('destinataire')->getData();
                $transfertData = $form->get('transfert')->getData();

                // Assurez-vous d'associer le client au transfert
                $transfertData->setClient($clientData);

                // Persiste les entités en base de données
                $em->persist($clientData);
                $em->persist($destinataireData);
                $em->persist($transfertData);

                // Utiliser une transaction pour garantir la cohérence des données
                $em->beginTransaction();

                // Flush dans la base de données
                $em->flush();

                // Commit de la transaction
                $em->commit();

                //Message flash pour informer de la réussite
                $this->addFlash('success', 'La transaction a été créée avec succès.');

                // Redirection vers de la liste des transactions
                return $this->redirectToRoute('admin_transaction_index');
            }
        } catch (\Exception $e) {
            // Rollback de la transaction en cas d'erreur
            $em->rollBack();

            // Message flash pour informer de l'échec
            $this->addFlash('error', 'Une erreur s\'est produite lors de la création de la transaction.');

            // Afficher les détails de l'erreur pour le débogage
            dump($e->getMessage());

            // Redirection vers la page du formulaire
    return $this->redirectToRoute('admin_transaction_create');
        }

        return $this->render('pages/admin/transaction/create.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorsFromForm($child);
            }
        }

        return $errors;
    }
}
