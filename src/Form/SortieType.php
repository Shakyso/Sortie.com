<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la sortie'] )
            ->add('dateHeureDebut', DateType::class, ['label' => 'Commence le'])
            ->add('duree', TimeType::class, ['label' => 'Durée Maximal'])
            ->add('dateLimiteInscription', DateType::class, ['label' => 'S\'inscrir avant le :'])
            ->add('nbInscriptionMax', NumberType::class, ['label' => 'Nombre max participant'])
            ->add('infosSortie', TextareaType::class, ['label' => 'Information'])
            ->add('siteOrganisateur', TextType::class, ['label' => 'Lieux'])
            ->add('accueille')
            ->add('etat')
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
