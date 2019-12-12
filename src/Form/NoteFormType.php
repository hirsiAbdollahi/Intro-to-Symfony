<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NoteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', ChoiceType::class, [
                'choices' => range(0,10),
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length ([
                        'min'=>50,
                        'minMessage'=>'Votre commmentaire doit contenir au moins 50 caracteres.',
                        'max' => 500,
                        'maxMessage'=>'Votre commmentaire ne peut depasser les 500 caracteres.'
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
