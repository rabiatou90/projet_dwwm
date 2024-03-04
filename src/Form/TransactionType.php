<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Destinataire;
use App\Entity\Transfert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $client = $options['data']['client'] ?? null;
        $destinataire = $options['data']['destinataire'] ?? null;
        $transfert = $options['data']['transfert'] ?? null;

        $builder
        ->add('client', ClientType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le prénom est obligatoire']),
            ],
        ])
        ->add('destinataire', DestinataireType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le destinataire est obligatoire']),
            ],
        ])
        ->add('transfert', TransfertType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le code de transfert est obligatoire']),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Utilisez 'data_class' à false pour éviter la nécessité d'une classe associée
            
        ]);
    }
}


