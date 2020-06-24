<?php

namespace App\Form;

use App\Entity\Messaging;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('content')
            ->add('author', EntityType::class, [
                'class'=>User::class,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => false,
                'by_reference' => false,
            ])            ->add('destinator', EntityType::class, [
                'class'=>User::class,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messaging::class,
        ]);
    }
}
