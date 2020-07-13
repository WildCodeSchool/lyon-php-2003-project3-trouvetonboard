<?php

namespace App\Form;

use App\Entity\Advisor;
use App\Entity\Enterprise;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\IdenticalToValidator;
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
            ->add('firstName', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre prénom.',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Il ne peut y avoir de nombre dans votre prénom.',
                    ]),
                ],
            ])
            ->add('lastName', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre nom.',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Il ne peut y avoir de nombre dans votre nom.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'required' => true,
                'mapped' => true,
                'first_options'  => [
                    'label' => false,
                    'help' => 'Votre mot de passe doit comporter au moins 8 caractères',
                    'attr' => [
                        'placeholder' => 'Mot de passe']],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe']],
                'invalid_message' => 'Les mots de passe doivent être identiques !',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 40,
                        'maxMessage' => 'Votre mot de passe est limité à {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Vous êtes',
                'choices'  => [
                    'une entreprise' => 'enterprise',
                    'un advisor' => 'advisor',
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
