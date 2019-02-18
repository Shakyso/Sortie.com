<?php

namespace App\Form;

use App\Entity\Lieu;

use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;


use App\Repository\VilleRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la sortie'] , ['attr' => ['id' => Sortie::class]])
            ->add('dateHeureDebut', DateTimeType::class, ['label' => 'Commence le'])
            ->add('duree', DateIntervalType::class, ['label' => 'Durée Maximal'])
            ->add('dateLimiteInscription', DateType::class, ['label' => 'S\'inscrir avant le :'])
            ->add('nbInscriptionMax', IntegerType::class, ['label' => 'Nombre max participant'])
            ->add('infosSortie', TextareaType::class, ['label' => 'Information'])
            ->add('lieu',EntityType::class, array(
                'multiple' => false,
                'label' => 'Votre lieu',
                'expanded' => false,
                'class' => Lieu::class,
                'choice_label' => 'nom'
                ))
            ->add('save and published', SubmitType::class, [
                'attr' =>  ['class' =>'savepub'],
                'label' => 'Save and Published'

            ])
            ->add('save', SubmitType::class, [
                 'attr' => ['class'=>'save'],
                'label' => 'Save'
    ]);
                }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

}
