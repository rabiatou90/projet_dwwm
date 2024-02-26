<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', ClientType::class, [
                'label' => false,
            ])
            ->add('destinataire', DestinataireType::class, [
                'label' => false,
            ])
            ->add('transfert', TransfertType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Utilisez 'data_class' à false pour éviter la nécessité d'une classe associée
            'data_class' => null,
        ]);
    }
}
