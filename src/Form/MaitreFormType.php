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
use Vich\UploaderBundle\Form\Type\VichImageType;

class MaitreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('fullName', TextType::class, [])
            ->add('dateBirth', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('specialty', TextType::class, [])
            ->add('numCin', IntegerType::class, [
                'attr' => array('min' => 00000000, 'max' => 99999999)

            ])
            ->add('imageCinAvant', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
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
