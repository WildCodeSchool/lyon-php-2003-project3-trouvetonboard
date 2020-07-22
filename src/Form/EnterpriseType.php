<?php

namespace App\Form;

use App\Entity\Enterprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EnterpriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ["label" => "Nom de l'entreprise"])
            ->add('address', null, ["label" => "Adresse"])
            ->add('zipCode', null, ["label" => "Code postal"])
            ->add('city', null, ["label" => "Ville"])
            ->add('legelRepresentative', null, ["label" => "Représentant légal"])
            ->add('websiteLink', null, [
                "label" => "Site web",
                'attr' => [
                    'placeholder' => 'Insérez ici le lien vers votre site',
                ],
                ])
            ->add('linkedinLink', null, [
                "label" => "LinkedIn",
                'attr' => [
                    'placeholder' => 'Insérez ici le lien vers votre profil LinkedIn',
                ],
                ])
            ->add('brochureFile', VichFileType::class, [
                'label' => "Plaquette",
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enterprise::class,
        ]);
    }
}
