<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SigninFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "attr" => [
                    "placeholder" => "you@insat.ucar.tn"
                ],
                "label" => "Email Address"
            ])
            ->add('password', PasswordType::class, [
                "attr" => [
                    "placeholder" => "Enter your password"
                ]
            ])
            ->add('submit', SubmitType::class, [
                "attr" => [
                    "class" => "primary-btn"
                ],
                "label" => "Sign In"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
