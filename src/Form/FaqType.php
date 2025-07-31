<?php
// src/Form/FaqType.php

namespace App\Form;

use App\Entity\Faqs;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class)
            ->add('answer', TextareaType::class, [
                'attr' => [
                    'data-controller' => 'tinymce',
                    'data-action' => 'turbo:load->tinymce#createEditor',
                ]
            ]); // or use TextareaType
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Faqs::class,
        ]);
    }
}
