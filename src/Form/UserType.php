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
            ->add('email', null, ["label" => "Adesse mail: "])
            //->add('roles')
            ->add('password', null, ["label" => "Mot de passe: "])
            ->add('firstName', null, ["label" => "Prénom: "])
            ->add('lastName', null, ["label" => "Nom: "])
            ->add('gender', ChoiceType::class, [
                "choices" => [
                    "Homme" => "Homme",
                    "Femme" => "Femme",
                    "Autre" => "Autre",
                    ]
            ])
            ->add('nationality', null, ["label" => "Natiaonalitée: "])
            ->add('lastName', null, ["label" => "Nom: "])
            ->add('birthday', DateType::class, [
                "label" => "Date de naissance: ",
                "placeholder"  => ['year' => 'Année :', 'month' => 'Mois :', 'day' => 'Jour :'],
                'years' => range(1900, 2020),
            ])
            ->add('phone', null, ["label" => "Téléphone: "])
            ->add('address', null, ["label" => "Adresse:"])
            ->add('postCode', null, ["label" => "Code postal: "])
            ->add('city', null, ["label" => "Ville: "])
            ->add('pictureLink', null, ["label" => "Photo: "])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
