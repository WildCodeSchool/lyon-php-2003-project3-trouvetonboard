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
            ->add('isAlreadyBoardMember')
            ->add('linkedinLink')
            ->add('cvLink')
            ->add('paymentStatus')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advisor::class,
        ]);
    }
}
