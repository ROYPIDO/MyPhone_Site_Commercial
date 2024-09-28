<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Produit;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('prix')
            ->add('quantity')
            ->add('ref')
            ->add('image', FileType::class,[
                    'label' =>'Chargez une image',
                    'data_class' => null,
                    'required' =>false

            ])
            ->add('category', EntityType::class,[
                    'class' => Category::class,
                    'choice_label' => 'name'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
