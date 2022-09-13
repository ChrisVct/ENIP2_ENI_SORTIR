<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class,[
                'label'         =>  "Campus",
                'class'         =>  Campus::class,
                'choice_label'  =>  'name',
                'required'      =>  false,
            ])
            ->add('name', TextareaType::class,[
                'label'     =>  'Le nom de la sortie contient',
                'required'  =>  false,
            ])
            ->add('minDate', DateType::class,[
                'mapped'    =>  false,
                'required'  =>  false,
                ])
            ->add('maxDate',DateType::class,[
                'mapped'    =>  false,
                'required'  =>  false,
            ])
            ->add('checkBoxOrganizer',CheckboxType::class,[
                'label'     => 'Sortie dont je suis l\'organisateur/trice',
                'mapped'    =>  false,
                'required'  =>  false,
            ])
            ->add('checkBoxRegistred',CheckboxType::class,[
                'label'     =>  'Sorties auxquelles je suis inscrits/es',
                'mapped'    =>  false,
                'required'  =>  false,
            ])
            ->add('checkBoxNotRegistred',CheckboxType::class,[
                'label'     =>  'Sorties auxquelles je ne suis pas inscrits/es',
                'mapped'    =>  false,
                'required'  =>  false,
            ])
            ->add('checkBoxEventsPassed',CheckboxType::class,[
                'label'     =>  'Sorties passées',
                'mapped'    =>  false,
                'required'  =>  false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'required'=> false,
        ]);
    }
}
