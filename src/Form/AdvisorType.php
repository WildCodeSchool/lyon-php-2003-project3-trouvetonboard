<?php

namespace App\Form;

use App\Entity\Advisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvisorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isAlreadyBoardMember', null, ["label" => "Avez-vous déjà pris part à un board d'advisor ?"])
            ->add('linkedinLink', null, ['label' => "modif upload LinkedIn"])
            ->add('cvLink', null, ['label' => "modif upload CV"])
            ->add('paymentStatus', null, ['label' => "Votre abonnement"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advisor::class,
        ]);
    }
}
