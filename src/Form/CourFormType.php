<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Cour;
use App\Entity\Matiere;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CourFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomCour', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),

                ],
            ])
            ->add('descriptionCour', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 3,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 300,
                    ]),

                ],
            ])
            ->add('imageFile', VichFileType::class, [
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,


            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => function (Matiere $matiere) {
                    return $matiere->getNomMatiere() . '-' . $matiere->getNiveau()->getNumNiveau();
                },


            ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cour::class,
        ]);
    }
}
