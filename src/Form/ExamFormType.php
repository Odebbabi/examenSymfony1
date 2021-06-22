<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Examen;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ExamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeExam', ChoiceType::class, [
                'choices' => [
                    '' => [
                        '? ?' => '0',
                        'trimestre' => 'Trimestre',
                        'évaluation' => 'Evaluation',
                    ]
                ],
                'label' => false
            ])
            ->add('semestre', ChoiceType::class, [
                'choices' => [
                    '' => [
                        '? ?' => '0',
                        '1' => 'premiere',
                        '2' => 'deuxieme',
                        '3' => 'troisieme',
                    ]
                ],
                'label' => false
            ])
            ->add('filePDF', VichFileType::class, [
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
            ])
            ->add('correctionPDF', VichFileType::class, [
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => function (Matiere $matiere) {
                    return 'Nom:   ' . $matiere->getNomMatiere() . '  -  ' . $matiere->getNiveau()->getNumNiveau() ;
                },
//. '  - ' . $matiere->getMatiere()->getNiveau()->getNumNiveau()

            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Examen::class,
        ]);
    }
}
