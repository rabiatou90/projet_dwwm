<?php

namespace App\Form;

use App\Entity\Destinataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DestinataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire', 'groups' => ['creation']]),
                ]
            ])
            ->add('nom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('contact', TextType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destinataire::class,
            
        ]);
    }
}
