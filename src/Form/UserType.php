<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email',  EmailType::class, [
            'attr' => ['class' =>  'form-control'],
        ])
        ->add('roles', ChoiceType::class, array(
            'label' => 'Roles',
            'attr' => ['class' => 'form-control'],
            'choices' => 
            [
                'User' => 'ROLE_USER',
                'Client' => 'ROLE_CUSTOMER',
                'Admin' => 'ROLE_ADMIN',
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => true,
            )
        )
        ->add('plainPassword',  PasswordType::class, [
            'label' => 'mot de passe',
            'attr' => ['class' =>  'form-control'],
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'constraints' => [
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe dois faire au minimum 6 caractéres',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ]);
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                 // transform the array to a string
                 return count($rolesArray)? $rolesArray[0]: null;
            },
            function ($rolesString) {
                 // transform the string back to an array
                 return [$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
