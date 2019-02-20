<?php

/**
 * Created by PhpStorm.
 * User: tdelmas2017
 * Date: 18/02/2019
 * Time: 15:56
 */

namespace App\Form;

use App\Entity\Lieu;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\Ville;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('latitude')
            ->add('longitude')
            ->add('ville', EntityType::class, array(
                'multiple' => false,
                'label' => 'Votre ville',
                'expanded' => false,
                'class' => Ville::class,
                'choice_label' => 'nom'
            ))
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
