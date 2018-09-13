<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',             EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control form-control-alternative',
                    'placeholder' => 'Email'
                )
            ))
            ->add('username',          TextType::class, array(
                'attr' => array(
                    'class' => 'form-control form-control-alternative',
                    'placeholder' => 'Nom d\'utilisateur'
                )
            ))
            ->add('rawPassword',       RepeatedType::class, array(
                  'type'            => PasswordType::class,
                  'invalid_message' => 'Les mots de passe doivent correspondre.',
                  'first_options'  => array('attr' => array('class' => 'form-control form-control-alternative', 'placeholder' => 'Mot de passe')),
                  'second_options' => array('attr' => array('class' => 'form-control form-control-alternative', 'placeholder' => 'Confirmez le mot de passe')),
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary btn-block btn-lg'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
