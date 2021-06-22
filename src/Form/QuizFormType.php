<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Matiere;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

class QuizFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomQuiz', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 300,
                    ]),

                ],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'label'=> false,
                'required'=> false,
                'data' => new \DateTime('now')

            ])
            ->add('difficulte', ChoiceType::class, [
                'choices' => [
                    '' => [
                        'facile' => 'FACILE',
                        'moyen' => 'MOYEN',
                        'difficile' => 'DIFFICILE',
                    ]
                ],
                'label' => false
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => function (Matiere $matiere) {
                    return  $matiere->getNomMatiere() . '  -  ' . $matiere->getNiveau()->getNumNiveau();
                },

            ])
            ->add('numQuestion',IntegerType::class,[
                'attr'=> array('min' =>1, 'max' =>100),
                'required'=> false,

            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'multiple'=>true,
                'expanded' => true,
                'choice_label' => function (Question $question) {
                    return  $question->getNomQuestion() ;
                },
            ])
            /*->add('comments', EntityType::class, [
                'class' => Comment::class,
                'required' => false,
                'label' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function (Comment $comment) {
                    return $comment->getText();
                },


            ])*/
            /*->add('user', EntityType::class, [
                'class' => User::class,
                'required' => false,
                'label' => false,
                'expanded' => true,
                'choice_label' => function (User $user) {
                    return $user->getFullName();
                },


            ])*/;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
