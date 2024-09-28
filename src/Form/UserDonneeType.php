<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserDonneeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class, [
                'required' => true
            ])
            ->add('prenom', TextType::class, [
                'required' => true
            ])
            ->add('adresse', TextType::class, [
                'required' => true
            ])
            ->add('ville', TextType::class, [
                'required' => true
            ])
            ->add('cp', TextType::class, [
                'required' => true
            ])
            ->add('tel', TextType::class, [
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
