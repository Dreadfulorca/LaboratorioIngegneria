<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movement;
use App\Enum\MovementType as MT;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Ammontare',
                'html5' => true,
                'scale' => 2, // UI: 2 decimali; la regex nel model fa fede
                'attr' => [
                    'step' => '0.01',
                    'min'  => '0.01',
                    'inputmode' => 'decimal',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Descrizione',
                'attr' => ['maxlength' => 100],
            ])
            ->add('date', DateType::class, [
                'label'  => 'Data',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'data'   => new \DateTimeImmutable('today'),
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Entrata' => MT::INCOME,
                    'Uscita'  => MT::EXPENSE,
                ],
                'expanded' => true, // radio
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Seleziona categoria',
                'required' => false, // obbligatoria solo per Uscita (validator di classe)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movement::class,
        ]);
    }
}