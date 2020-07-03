<?php

namespace App\Form;

use App\Entity\Enterprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnterpriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ["label" => "Nom de l'entreprise :"])
            ->add('address', null, ["label" => "Adresse :"])
            ->add('zipCode', null, ["label" => "Code postal :"])
            ->add('city', null, ["label" => "Ville :"])
            ->add('legelRepresentative', null, ["label" => "Représentant légal :"])
            ->add('websiteLink', null, ["label" => "Site web :"])
            ->add('linkedinLink', null, ["label" => "LinkedIn :"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enterprise::class,
        ]);
    }
}
