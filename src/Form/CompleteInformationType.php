<?php

namespace App\Form;

use App\Entity\Disease;
use App\Entity\Drugs;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialNumber',TextType::class,[
                'label'=>'Insert your Security Social Number'
            ])
            ->add('name', TextType::class, ['label'=> 'Your full name'])
            ->add('address', TextType::class, ['label'=> 'Your address'])
            ->add('city', TextType::class, ['label'=>'Your city']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
