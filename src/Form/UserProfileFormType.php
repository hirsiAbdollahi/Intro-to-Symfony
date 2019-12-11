<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un email.']),
                    new Email(['message' => 'Veuillez rentrer une adresse valide.'])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un pseudo.']),
                    new Regex([
                        'pattern' => '/^[a-z0-9-_]+$/i',
                        'message' => 'Le pseudo ne peut contenir que des caractères alphanumériques.'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le pseudo doit contenir au moins 3 caractères',
                        'max' => 40,
                        'maxMessage' => 'Le pseudo ne peut contenir plus de 40 caractères',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
