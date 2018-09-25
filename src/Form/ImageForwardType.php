<?php

namespace App\Form;

use App\Entity\ImageForward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageForwardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'attr' => array(
                    'class' => 'fas fa-edit'
                ),
                'label' => false,
                'required' => false
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => array('registration'),
            'data_class' => ImageForward::class,
        ]);
    }
}
