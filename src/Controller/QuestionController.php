<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class QuestionController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("dashboard/maitre/question", name="question_index")
     */
    public function questionIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $questions = $this->getDoctrine()->getManager()->getRepository(Question::class)->findAll();
        $user = $this->security->getUser();


        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/question/add", name="question_add")
     */
    public function questionAdd(Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $question = new Question();

        $proposition1 = new Proposition();
        $proposition1->setNomProposition('proposition1');
        $proposition1->setCorrecte('true');
        $question->getProposition()->add($proposition1);

        $proposition2 = new Proposition();
        $proposition2->setNomProposition('proposition2');
        $proposition2->setCorrecte('true');
        $question->getProposition()->add($proposition2);

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($question);
            $proposition1->setQuestion($question);
            $proposition2->setQuestion($question);
            $em->flush();

            return $this->redirectToRoute('question_index');
        }
        $user = $this->security->getUser();

        return $this->render('question/add.html.twig', [
            'question' => $question,
            'questionform' => $form->createView(),
            'user' => $user,


        ]);
    }

    /**
     * @Route("dashboard/maitre/question/view/{id}", name="question_show")
     */
    public function questionShow(Question $question): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $user = $this->security->getUser();

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'user' => $user,


        ]);
    }


    /**
     * @Route("dashboard/maitre/question/edit/{id}", name="question_edit")
     */
    public function questionEdit(Request $request, Question $question): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $originalProposition = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($question->getProposition() as $proposition) {
            $originalProposition->add($proposition);
        }

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // remove the relationship between the tag and the Task
            foreach ($originalProposition as $proposition) {
                if (false === $question->getProposition()->contains($proposition)) {
                    // remove the Task from the Tag
//                    $proposition->getQuestion()->removeElement($question);
                    $proposition->setQuestion(null);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $proposition->setQuestion(null);

                    $em->persist($proposition);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
            }
            $em->persist($question);

            $em->flush();
            return $this->redirectToRoute('question_index');
        }
        $user = $this->security->getUser();

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'questionform' => $form->createView(),
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/question/delite/{id}", name="question_delete")
     */
    public function questionDelete(Question $question): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();
        $em = $this->getDoctrine()->getManager();
        foreach ($question->getProposition() as $proposition) {
            $question->removeProposition($proposition);
            $em->remove($proposition);
        }
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('question_index');

    }

}
