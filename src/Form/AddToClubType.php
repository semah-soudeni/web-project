<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToClubType extends AbstractType
{
    public function __construct(private Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $studentId = $user instanceof Etudiant ? (string) $user->getId() : null;
        $studentemail = $user instanceof Etudiant ? (string) $user->getEmail() : null;
        $studentphone = $user instanceof Etudiant ? (string) $user->getPhone() : null;

        $builder
            ->add('club', ChoiceType::class, [
                'label' => 'Club',
                'mapped' => false,
                'choices' => [
                    'ACM' => 14,
                    'AERO' => 11,
                    'CIM' => 15,
                    'IEEE' => 13,
                    'SECU' => 12,
                    'THEA' => 16,
                    'PRESS' => 17,
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Member Role',
                'choices' => [
                    'Member' => 'member',
                    'Admin' => 'admin',
                    'VPA' => 'vpa',
                    'VPT' => 'vpt',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'mapped' => false,
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'mapped' => false,
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                "mapped" => false,
                "data" => $studentemail,
                "disabled" => true,
                'required' => false,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone',
                "mapped" => false,
                "data" => $studentphone,
                "disabled" => true,
                'required' => false,
            ])
            ->add('studentId', TextType::class, [
                'label' => 'Student ID',
                'mapped' => false,
                'data' => $studentId,
                "disabled" => true
                ,'required' => false,
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Academic Year',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    '1st year' => 1,
                    '2nd year' => 2,
                    '3rd year' => 3,
                    '4th year' => 4,
                    '5th year' => 5,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Join Club',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}