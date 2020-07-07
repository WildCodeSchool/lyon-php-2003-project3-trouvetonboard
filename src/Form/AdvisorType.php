<?php

namespace App\Form;

use App\Entity\Advisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
            ->add('linkedinLink', null, [
                'label' => "LinkedIn :",
                'attr' => [
                    'placeholder' => 'Insérez ici le lien vers votre profil LinkedIn',
                ],
                ])
            ->add('cvLinkFile', VichFileType::class, [
                'label' => "CV :",
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advisor::class,
        ]);
    }
}
