<?php

namespace App\Form;

use App\Entity\Profile;
use App\Repository\SkillRepository;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isRequest')
            ->add('isPropose')
            ->add('paymentType')
            ->add('title')
            ->add('dateCreation')
            ->add('skills', EntityType::class, [
                "class" => Skill::class,
                'query_builder' => function (SkillRepository $ac) {
                        return $ac->createQueryBuilder('skill')->orderBy('skill . name', 'ASC');
                },
                'choice_label' => 'name',
                "multiple" => true,
                "expanded" => true,
                'by_reference' => false,])
            ->add('enterprise')
            ->add('advisor')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
