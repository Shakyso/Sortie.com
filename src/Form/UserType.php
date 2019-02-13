<?php
/**
 * Created by PhpStorm.
 * User: tdelmas2017
 * Date: 13/02/2019
 * Time: 15:02
 */
namespace App\Form;

use App\Entity\Site;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Pseudo : ', 'empty_data' => ''])
            ->add('telephone', TelType::class, ['label' => 'Numéro de téléphone : ', 'required' => false])
            ->add('mail', TextType::class, ['label' => 'Adresse mail : ', 'required' => false])
            //->add('password', PasswordType::class, ['label' => 'Nouveau mot de passe : ', 'required' => false, 'empty_data' => ''])
            //->add('newPassword', PasswordType::class, ['label' => 'Confirmation du nouveau mot de passe : ', 'required' => false])
            ->add('name', TextType::class, ['label' => 'Prénom : ', 'required' => false])
            ->add('lastname', TextType::class, ['label' => 'Nom de famille : ', 'required' => false])
            ->add('site', EntityType::class, [
                'label' => 'Sites : ',
                'class' => Site::class,
                'choice_label' => 'nom',
            ])
            ->add('photo', FileType::class, ['label' => 'Ma photo : ', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Modifier'])

        ;
    }


}