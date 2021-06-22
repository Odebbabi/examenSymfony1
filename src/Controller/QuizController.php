<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\CommentFormType;
use App\Form\QuizFormType;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class QuizController extends AbstractController
{

    /**
     * @Route("dashboard/maitre/quiz", name="quiz_index")
     */
    public function quizIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $quizzes = $this->getDoctrine()->getManager()->getRepository(Quiz::class)->findAll();
        $user = $this->getUser();


        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
            'user'=> $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/quiz/add", name="quiz_add")
     */
    public function quizAdd(Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $quiz = new Quiz();
        $user = $this->getUser();
        $quiz->setUser($user);

        $form = $this->createForm(QuizFormType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($quiz);
            $em->flush();

            $this->addFlash('success',' Bon travail! quiz ajouté avec succès ');

            return $this->redirectToRoute('quiz_index');
        }
        $user = $this->getUser();

        return $this->render('quiz/add.html.twig', [
            'quiz' => $quiz,
            'quizform' => $form->createView(),
            'user'=> $user,


        ]);
    }

    /**
     * @Route("dashboard/maitre/quiz/view/{id}", name="quiz_show")
     */
    public function quizShow(Quiz $quiz): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();
        $user = $this->getUser();

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
            'user'=> $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/quiz/edit/{id}", name="quiz_edit")
     */
    public function quizEdit(Request $request, Quiz $quiz): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(QuizFormType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail !! quiz modifié avec succès !');
            return $this->redirectToRoute('quiz_index');
        }
        $user = $this->getUser();

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'quizform' => $form->createView(),
            'user'=> $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/quiz/delite/{id}", name="quiz_delete")
     */
    public function quizDelete(Quiz $quiz): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($quiz);
        $em->flush();
        $this->addFlash('success',' Bon travail !! quiz supprimé avec succès !');

        return $this->redirectToRoute('quiz_index');

    }

    /**
     * @Route("dashboard/eleve/quiz", name="quiz_eleve_index")
     */
    public function quizEleveIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();

        $quizzes = $this->getDoctrine()->getManager()->getRepository(Quiz::class)->findAll();

        return $this->render('quiz/indexEleve.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * @Route("dashboard/eleve/quiz/pass/{id}", name="quiz_eleve_pass")
     */
    public function quizElevePass(Quiz $quiz,Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();

        $comments = $quiz->getComments();

        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setQuiz($quiz);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->getUri());
        }


        return $this->render('quiz/pass.html.twig', [
            'quiz' => $quiz,
            'comment' => $comment,
            'commentform' => $form->createView(),
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("dashboard/eleve/quiz/validate/{id}", name="quiz_eleve_validate", methods={"POST", "GET"})
     */
    public function quizEleveValidate(Quiz $quiz, Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();
        // reponses user
        $data = $request->request->get('data');

        if (empty($data)) {
            return new JsonResponse(['msg' =>'Veillez répondre aux questions'], 200);
        } else {
            $data = explode('&', $data);
            $reponsesUser = [];
            foreach ($data as $item) {
                $idQ = intval(substr($item, 0, strpos($item, '-')));
                $idP = intval(substr(
                    $item,
                    strpos($item, '-') + 1,
                    strpos($item, '-', strpos($item, '-'))
                ));

                if (isset($reponsesUser[$idQ])) {
                    $ancValue = $reponsesUser[$idQ];
                    array_push($ancValue, $idP);
                    $reponsesUser[$idQ] = $ancValue;
                } else {
                    $reponsesUser[$idQ] = [$idP];
                }
            }

        $nbrQuestionCorrecte = 0;
        foreach ($quiz->getQuestion() as $question) {
            if (isset($reponsesUser[$question->getId()])) {
                $propCorrect = [];
                foreach ($question->getProposition() as $prop) {
                    if ($prop->getCorrecte() == true)
                        array_push($propCorrect, $prop->getId());
                }
                if (count($propCorrect) == count($reponsesUser[$question->getId()])) {
                    foreach ($propCorrect as $pc) {
                        if (!in_array($pc, $reponsesUser[$question->getId()]))
                            continue 2;
                    }
                    $nbrQuestionCorrecte++;
                }
            }
        }
        $moyenne = round ((($nbrQuestionCorrecte / count($quiz->getQuestion())) * 100),2,PHP_ROUND_HALF_UP);

        return new JsonResponse(['moyenne'=> $moyenne], 200);
        }

//        return $this->render('quiz/validate.html.twig', [
//            'quiz' => $quiz,
//            'moyenne' => $moyenne,
//            ]);
//    }

}}
