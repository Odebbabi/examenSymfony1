<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),

                ],
            ])
            ->add('fullName', TextType::class, [])
            ->add('dateBirth', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('accountType', ChoiceType::class, [
                'choices' => [
                    '' => [
                        'Vous etes ?' => '0',
                        'eleve' => 'ELEVE',
                        'maitre(sse)' => 'MAITRE',

                    ]
                ],
                'label' => false
            ])
            ->add('niveau', IntegerType::class, [
                'attr' => array('min' => 1, 'max' => 6)
            ])
            ->add('numCarteInscription', IntegerType::class, [])
            ->add('specialty', TextType::class, [])
            ->add('numCin', IntegerType::class, [
                'attr' => array('min' => 00000000, 'max' => 99999999)
            ])
            ->add('imageCarteInscription', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,

            ])
            ->add('imageCinAvant', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'download_label' => false,
            ])
            ->add('imageCinArriere', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
            ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
