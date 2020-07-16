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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                "label" => "Civilité",
                "choices" => [
                    "Monsieur" => "H",
                    "Madame" => "F",
                ]
            ])
            ->add('firstName', null, [
                "label" => "Prénom ",
                'empty_data' => '',
            ])
            ->add('lastName', null, [
                "label" => "Nom ",
                'empty_data' => '',
            ])
            ->add('email', null, ["label" => "Adresse mail "])
            //->add('roles')
            ->add('nationality', null, ["label" => "Nationalité "])
            ->add('birthday', DateType::class, [
                "label" => "Date de naissance ",
                'format' => 'dd MM yyyy',
                "placeholder"  => ['year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
                'years' => range(2020, 1920),
            ])
            ->add('phone', null, ["label" => "Téléphone"])
            ->add('address', null, ["label" => "Adresse"])
            ->add(
                'postCode',
                TextType::class,
                [
                "label" => "Code postal",
                ]
            )
            ->add('city', null, ["label" => "Ville"])
            ->add('country', null, ["label" => "Pays de résidence"])
            ->add('pictureLink', null, ["label" => "Photo"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
