<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditNotifForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business', CheckboxType::class, [
                'label' => 'Business approach',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',
                    'id' => 'flexSwitchCheckChecked',
                    'checked' => true
                ]
            ])
            ->add('auction', CheckboxType::class, [
                'label' => 'Auction status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',
                    'id' => 'flexSwitchCheckChecked',
                    'checked' => true
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
