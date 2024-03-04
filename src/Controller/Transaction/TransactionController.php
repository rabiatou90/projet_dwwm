<?php

namespace App\Controller\Transaction;

use App\Entity\Client;
use App\Entity\Transfert;
use App\Entity\Destinataire;
use App\Form\TransactionType;
use App\Entity\TransactionData;
use App\Repository\ClientRepository;
use App\Repository\TransfertRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DestinataireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('admin/transaction/list', name: 'admin_transaction_index', methods: ['GET'])]
    public function indexAdmin(TransfertRepository $transfertRepository, ClientRepository $clientRepository, DestinataireRepository $destinataireRepository): Response

    {
        $transferts = $transfertRepository->findAll();
        $clients = $clientRepository->findAll();
        $destinataires = $destinataireRepository->findAll();

        $transactions = [];

        foreach ($transferts as $transfert) {
            $transaction = [
                'id' => $transfert->getId(),
                'client' => $transfert->getClient(),
                'destinataire' => $transfert->getDestinataire(),
                'montant_du_transfert' => $transfert->getMontantDuTransfert()
            ];
            $transactions[] = $transaction;
        }


        return $this->render('pages/admin/transaction/index.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    #[Route('admin/transaction/create', name: 'admin_transaction_create', methods: ['GET', 'POST'])]
public function createAdmin(Request $request): Response
{
    $user = $this->tokenStorage->getToken()->getUser();

        $client = new Client();
        $destinataire = new Destinataire();
        $transfert = new Transfert();

         // Associez l'utilisateur courant aux entités Client, Destinataire et Transfert 
        $client->setUser($user);
        $destinataire->setUser($user);
        $transfert->setUser($user);
    

        // Générez le code_de_transfert
        $transfert->generateCodeDeTransfert();

        
        $form = $this->createForm(TransactionType::class, [
            'client' => $client,
            'destinataire' => $destinataire,
            'transfert' => $transfert,
        ], [
            'validation_groups' => $this->getValidationGroups($client, $destinataire, $transfert),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez les données du formulaire
            $formData = $form->getData();

            // Instancier les entités avec les données du formulaire
            $client = $formData['client'];
            $destinataire = $formData['destinataire'];
            $transfert = $formData['transfert'];

            // Assicie l'utilisateur courant aux entités Client, Destinataire et Transfert 
            $client->setUser($user);
            $destinataire->setUser($user);
            $transfert->setUser($user);

            // Associez le destinataire au transfert 
            $transfert->setDestinataire($destinataire);
            
            // Associez le client à votre destinataire et transfert
            $destinataire->setClient($client);
            $transfert->setClient($client);

            // Vérifiez que le prénom est correctement défini avant de persister le client
            if ($client->getPrenom() !== null && $client->getPrenom() !== '') {
                // Si le client n'existe pas encore en base de données, persistez-le
                if ($client->getId() === null) {
                    $this->entityManager->persist($client);
                }

                // Persistez les entités 
                $this->entityManager->persist($destinataire);
                $this->entityManager->persist($transfert);
                $this->entityManager->flush();

                $this->addFlash("success", "La transaction a été ajoutée.");

                return new RedirectResponse($this->generateUrl('admin_transaction_index'));
            } else {
                $this->addFlash("danger", "Le prénom du client n'est pas défini correctement.");
            }
        } else {
            $errors = $form->getErrors(true, true);
            foreach ($errors as $error) {
                $this->addFlash("danger", $error->getMessage());
            }
        }

        return $this->render('pages/admin/transaction/create.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors ?? [],
        ]);
    }

            


    
    #[Route('admin/transaction/show/{id}', name: 'admin_transaction_show', methods: ['GET'])]
public function showAdmin(Transfert $transfert): Response
{   
    $client = $transfert->getClient();
    $destinataire = $transfert->getDestinataire();

    $data = [
        'transfert' => [
            'id' => $transfert->getId(),
            'montant_du_transfert' => $transfert->getMontantDuTransfert(),
            // Ajoutez d'autres champs selon vos besoins
        ],
        'client' => [
            'prenom' => $client->getPrenom(),
            'nom' => $client->getNom(),
            // Ajoutez d'autres champs selon vos besoins
        ],
        'destinataire' => [
            'prenom' => $destinataire->getPrenom(),
            'nom' => $destinataire->getNom(),
            // Ajoutez d'autres champs selon vos besoins
        ],
    ];

    $transactionData = new TransactionData();
    $transactionData->setData($data);

    return $this->render('pages/admin/transaction/show.html.twig', [
        'transactionData' => $transactionData,
    ]);
}


    


    /**
     * Détermine les groupes de validation en fonction de l'état des entités.
     *
     * @param Client $client
     * @param Destinataire $destinataire
     * @param Transfert $transfert
     * @return array
     */
    private function getValidationGroups(Client $client, Destinataire $destinataire, Transfert $transfert): array
    {
        // Si le client existe déjà en base de données, utilisez le groupe de validation "update"
        if ($client->getId() !== null) {
            return ['update'];
        }

        // Sinon, utilise le groupe de validation "creation"
        return ['creation'];
    }
}
