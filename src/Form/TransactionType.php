<?php

namespace App\Form;

use App\Entity\Transfert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', ClientType::class, [
                'data' => $options['client'],
                'required' => true,
            ])
            ->add('destinataire', DestinataireType::class,  [
                'data' => $options['destinataire'],
            ])
            ->add('transfert', TransfertType::class, [
                'data' => $options['transfert'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfert::class,
            'client' => null,
            'destinataire' => null,
            'transfert' => new Transfert(),
        ]);
    }
}
