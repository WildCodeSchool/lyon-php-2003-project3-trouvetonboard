<?php

namespace App\Form;

use App\Entity\Advisor;
use App\Entity\Enterprise;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
            ->add('first_name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre prénom',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Il ne peut y avoir de nombre dans votre prénom',
                    ]),
                ],
            ])
            ->add('last_name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre nom',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Il ne peut y avoir de nombre dans votre nom',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 40,
                        'maxMessage' => 'Votre mot de passe est limité à {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Sélectionner votre type',
                'choices'  => [
                    'Entreprise' => 'enterprise',
                    'Advisor' => 'advisor',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'data_password' => PasswordType::class,
        ]);
    }
}
