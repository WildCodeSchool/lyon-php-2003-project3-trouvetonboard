<?php

namespace App\Form;

use App\Entity\Advisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvisorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'isAlreadyBoardMember',
                ChoiceType::class,
                [
                    "label" => "Avez-vous déjà pris part à un board d'advisor ?",
                    'choices' => [
                        ' ' => null,
                        'Oui' => true,
                        'Non' => false,
                    ]
                ]
            )
            ->add('linkedinLink', null, ['label' => "modif upload LinkedIn"])
            ->add('cvLink', null, ['label' => "modif upload CV"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advisor::class,
        ]);
    }
}
