<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('couleur')
            ->add('taille', ChoiceType::class, [
                'choices' => [
                    'S' => 'S',
                    'M' => 'm',
                    'L' => 'l',
                    'XL' => 'xl'
                ]
            ])
            ->add('collection')
            ->add('photo')
            ->add('prix')
            ->add('stock',ChoiceType::class, [
                'choices' => [
                    'In Stock' => true,
                    'Out of Stock' => false,
                ]
                ])
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
