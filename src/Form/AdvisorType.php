<?php

namespace App\Form;

use App\Entity\Advisor;
use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\CallbackTransformer;

class AdvisorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'profiles',
                ProfileType::class,
                [
                    "label" => " ",
                    'by_reference' => false

                ]
            )
            ->add(
                'isAlreadyBoardMember',
                ChoiceType::class,
                [
                    "label" => "Avez-vous déjà pris part à un board d'Advisors ?",
                    'choices' => [
                        ' ' => null,
                        'Oui' => true,
                        'Non' => false,
                    ]
                ]
            )
            ->add('linkedinLink', null, [
                'label' => "LinkedIn",
                'attr' => [
                    'placeholder' => 'Insérez ici le lien vers votre profil LinkedIn',
                ],
                ])
            ->add('cvLinkFile', VichFileType::class, [
                'label' => "CV",
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'download_link' => false,
            ])
        ;

        $builder->get('profiles')->addModelTransformer(
            new CallbackTransformer(
                function ($profilesAsProfile) {
                    // transform the array to an instace of
                    return $profilesAsProfile[0];
                },
                function ($profileAsProfiles) {
                    // transform an instance to array
                    $profiles = [];
                    $profiles[] = $profileAsProfiles;
                    return $profiles;
                },
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advisor::class,
        ]);
    }
}
