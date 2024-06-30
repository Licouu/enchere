<?php

namespace App\Form;


use App\Model\SearchData;

use App\Enum\Mood;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormEvents;


class MoodsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('moods', EnumType::class, [
            'class' => Mood::class,
            'mapped' => false,
            'expanded' => true,
            'multiple' => true 
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
