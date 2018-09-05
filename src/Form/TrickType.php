<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\ImageType;
use App\Form\ForwardImageType;
use App\Form\VideoType;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control form-control-alternative',
                    'type' => 'text',
                    'placeholder' => 'Nom de la figure')             
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'id' => 'textarea',
                    'placeholder' => 'Description de la figure')
            ))
            ->add('image_forward', ForwardImageType::class, array(
                'required' => false
            ))
            ->add('images', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('videos', CollectionType::class, array(
                'entry_type' => VideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
                ))
            ->add('trickGroup', EntityType::class, array(
                'attr' => array(
                    'class' => 'custom-select',
                    'id' => 'inputGroupSelect01'),
                'class' => TrickGroup::class,
                'choice_label' =>  'name',
                'multiple'  => false,
                'expanded'  => false,
                'label' => 'Groupe de la figure'
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
