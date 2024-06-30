<?php

namespace App\Form;

use App\Entity\Auction;
use App\Enum\Category;
use App\Enum\Mood;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class AuctionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 20,
                        'maxMessage' => 'Name cannot exceed {{ limit }} characters'
                    ])
                ]
            ])
            ->add('categorie', EnumType::class, [
                'mapped' => false,
                'class' => Category::class
            ])
            ->add('mood', EnumType::class, [
                'mapped' => false,
                'class' => Mood::class
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload in a valid image format',
                    ])
                ],
                'attr' => [
                    'accept'=> '.jpg, .jpeg, .png'
                ]
            ])
            ->add('musicFile', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000M',
                        'mimeTypes' => [
                            'application/octet-stream',
                            'audio/x-wav'
                        ],
                        'mimeTypesMessage' => 'Please upload in a valid audio format',
                    ])
                ]
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new Assert\Range([
                        'min' => (new \DateTimeImmutable())->modify('+1 day'),
                        'max' => (new \DateTimeImmutable())->modify('+1 month'),
                        'minMessage' => 'The end date must be in one month from now',
                        'maxMessage' => 'The end date cannot be more than a month away'
                    ])
                ]
            ])
            ->add('minPrice', null, [
                'constraints' => [
                    new Assert\Range(['min' => 0, 'max' => 1000, 'minMessage' => 'The minimum price must be between 0 and 1000.']),
                ],
            ])
            //->addEventListener()
            ->getForm()
        ;
    }

    /**public function eventForm(){

    }
    */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auction::class,
        ]);
    }
}
