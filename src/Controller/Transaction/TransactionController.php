<?php 

namespace App\Controller\Transaction;

use App\Entity\Transfert;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
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
        public function createAdmin(Request $request, EntityManagerInterface $entityManager): Response 
        { 
            $transfert = new Transfert();
            $form = $this->createForm(TransactionType::class, $transfert);
    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des entités en base de données
            $entityManager->persist($transfert);
            $entityManager->flush();
        
            // Redirection vers une autre page, par exemple la liste des transactions
            return $this->redirectToRoute('admin_transaction_index');
            }
            // dd ($form->all());
    
            return $this->render('pages/admin/transaction/create.html.twig', [
            'form' => $form->createView(),
            ]);
        }
    }
    // dd ($request->all());
    // die();
