<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UpdateProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'please enter a valid username']),
                    new Length(['min' => 4,
                        'minMessage' => 'Your username should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Your username should not be longer than 20 characters',
                    ])
                ],
                'error_bubbling' => true,
                'label' => false ,
                'attr' => ['placeholder' => 'Username']
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'message' => 'Please enter a valid email address.'
                    ]),
                ],
                'error_bubbling' => true,
                'label' => false,
                'attr' => ['class' => 'input','placeholder' => 'Email'],
            ])
            ->add('profilePicture', FileType::class,[
            'mapped' => false,
            'label' => false,
            'attr' => ['class'=>'edit-profile-picture custom-file-input', 'required' => false]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'save-form'],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
