<?php

namespace App\Form;

use App\Entity\Profile;
use App\Repository\SkillRepository;
use App\Entity\Skill;
use Faker\Provider\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'title',
                null,
                [
                    "label" => "Titre :",
                    'attr' => [
                        'placeholder' => 'Veuillez saisir un intitulé de poste ou titre'
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    "label" => "Description :",
                    'attr' => ['placeholder' => 'Veuillez décrire votre profil professionnel'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
