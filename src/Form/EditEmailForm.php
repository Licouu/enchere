<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditEmailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email1', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ]
            ])
            ->add('email2', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ]
            ])
            ->add('submit', SubmitType::class, [
            'label' => 'Submit',
            'attr' => [
                'class' => 'btn btn-primary',
                'id' => 'submit_email'
                ]
            ]);
    }
}
