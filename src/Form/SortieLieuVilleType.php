<?php
/**
 * Created by PhpStorm.
 * User: sbrechet2017
 * Date: 19/02/2019
 * Time: 09:06
 */

namespace App\Form;


use App\Entity\Lieu;

use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieLieuVilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rue', TextType::class, array(
                'disabled' => true,
                'label' => 'Rue'
            ))
            ->add('latitude', TextType::class, array(
                'disabled' => true,
                'label' => 'Latitude'
            ))
            ->add('longitude', TextType::class, array(
                'disabled' => true,
                'label' => 'Longitude'
            ))
            ->add('ville', EntityType::class, array(
                'multiple' => false,
                'label' => 'Votre ville',
                'expanded' => false,
                'class' => Ville::class,
                'choice_label' => 'nom'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
