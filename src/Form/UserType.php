<?php

namespace App\Form;

use App\Entity\Advisor;
use App\Entity\Enterprise;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                "label" => "Civilité",
                "choices" => [
                    "Monsieur" => "Monsieur",
                    "Madame" => "Madame",
                ]
            ])
            ->add('firstName', null, ["label" => "Prénom "])
            ->add('lastName', null, ["label" => "Nom "])
            ->add('email', null, ["label" => "Adresse mail "])
            //->add('roles')
            ->add('nationality', null, ["label" => "Nationalité "])
            ->add('lastName', null, ["label" => "Nom "])
            ->add('birthday', DateType::class, [
                "label" => "Date de naissance ",
                'format' => 'dd MM yyyy',
                "placeholder"  => ['year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
                'years' => range(1930, 2020),
            ])
            ->add('phone', null, ["label" => "Téléphone"])
            ->add('address', null, ["label" => "Adresse"])
            ->add('postCode', null, ["label" => "Code postal"])
            ->add('city', null, ["label" => "Ville"])
            ->add('country', null, ["label" => "Pays de résidence"])
            ->add('pictureLink', null, ["label" => "Photo"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
