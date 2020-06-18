<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isRequest')
            ->add('isPropose')
            ->add('paymentType')
            ->add('title')
            ->add('dateCreation')
            ->add('skills')
            ->add('enterprise')
            ->add('advisor')
            ->add('enterpriseProfiles')
            ->add('advisorProfiles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
