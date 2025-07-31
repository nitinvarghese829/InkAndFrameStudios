<?php

namespace App\Form;

use App\Entity\ContactUs;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name*',
                'label_attr' =>  [
                    'class' =>  "form-label required"
                ],
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 50,
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email*',
                'label_attr' =>  [
                    'class' =>  "form-label required"
                ],
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 100,
                ]
            ])
            ->add('phoneNo', NumberType::class, [
                'required' => false,
                'html5' => true,
                'label' => 'Phone',
                'label_attr' =>  [
                    'class' =>  "form-label"
                ],
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 15,
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => false,
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'maxlength' => 1000,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactUs::class,
        ]);
    }
}
