<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('roles', ChoiceType::class, [
                'constraints' => [
                    new NotBlank([
                        "message" => "Le rôle est obligatoire."
                    ])
                ],
                'choices'  => [
                    'Rôle utilisateur' => "ROLE_USER",
                    'Rôle administrateur' => "ROLE_ADMIN",
                ],
                'expanded' => false,
                'multiple' => true
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
