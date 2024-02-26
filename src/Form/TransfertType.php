<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Transfert;
use App\Entity\Destinataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_du_transfert')
            ->add('frais_envoie')
            ->add('montant_recu')
            ->add('mode_de_retrait', ChoiceType::class, [
                'label' => 'Mode de retrait',
                'choices' => [
                    'Orange Money' => 'orange_money',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'orange_money', // Valeurs cochées par défaut
            ])
            
            ->add('code_de_transfert', TextType::class, [
                'label' => 'Code de transfert',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true, // Pour rendre le champ en lecture seule
                ],
            ])
            ->add('moyen_de_paiement', ChoiceType::class, [
                'choices' => [
                    'Carte Bleue' => 'carte_bleue',
                    'Espèces' => 'especes',
                    'Paypal' => 'paypal',
                ],
                'multiple' => False, // Un seul choix possible
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transfert::class,
        ]);
    }

}
