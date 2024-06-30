<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inputPasswordold', PasswordType::class, [
                'label' => 'Old Password',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password'
                ]
            ])
            ->add('inputPasswordnew1', PasswordType::class, [
                'label' => 'New password',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password'
                ]
            ])
            ->add('inputPasswordnew2', PasswordType::class, [
                'label' => 'New password',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password'
                ]
            ])
            ->add('submit', SubmitType::class, [
            'label' => 'Submit',
            'attr' => [
                'class' => 'btn btn-primary',
                'id' => 'submit_password'
            ]
        ]);
    }
}
