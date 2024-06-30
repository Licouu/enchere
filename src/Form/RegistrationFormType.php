<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 18,
                        'maxMessage' => 'The first name cannot be longer than {{ limit }} characters',
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 18,
                        'maxMessage' => 'The last name cannot be longer than {{ limit }} characters',
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\Length([
                        'max' => 18,
                        'maxMessage' => 'The username cannot be longer than {{ limit }} characters',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email()
                ]
            ])
            ->add('agreeTerms', CheckboxType::class,     [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class)
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
        ]);
    }
}
