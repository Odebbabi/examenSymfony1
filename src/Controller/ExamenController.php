<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Examen;
use App\Form\CommentFormType;
use App\Form\ExamFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class ExamenController extends AbstractController
{


    /**
     * @Route("dashboard/maitre/examen", name="exam_index")
     */
    public function examIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $exams = $this->getDoctrine()->getManager()->getRepository(Examen::class)->findAll();
        $user = $this->getUser();

        return $this->render('examen/index.html.twig', [
            'exams' => $exams,
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/examen/add", name="exam_add")
     */
    public function examAdd (Request $request ): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $exam = new Examen();
        $user = $this->getUser();
        $exam->setUser($user);


        $form = $this->createForm(ExamFormType::class,$exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em->persist($exam);
            $em->flush();
            $this->addFlash('success',' Bon travail! examen ajouté avec succès ');

            return $this->redirectToRoute('exam_index');
        }
        $user = $this->getUser();

        return $this->render('examen/add.html.twig', [
            'exam' => $exam,
            'examform' => $form->createView(),
            'user' => $user,


        ]);
    }

    /**
     * @Route("dashboard/maitre/examen/view/{id}", name="exam_show")
     */
    public function examShow(Examen $exam): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();
        $user = $this->getUser();

        return $this->render('examen/show.html.twig', [
            'exam' => $exam,
            'user' => $user,

        ]);
    }
    /**
     * @Route("dashboard/maitre/examen/edit/{id}", name="exam_edit")
     */
    public function examEdit(Request $request, Examen $exam): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ExamFormType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail! examen modifié avec succès ');

            return $this->redirectToRoute( 'exam_index');
        }
        $user = $this->getUser();

        return $this->render('examen/edit.html.twig', [
            'exam' => $exam,
            'examform' => $form->createView(),
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/examen/delite/{id}", name="exam_delete")
     */
    public function examDelete(Examen $exam): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($exam);
        $em->flush();
        $this->addFlash('success',' Bon travail! examen supprimé avec succès ');

        return $this->redirectToRoute('exam_index');

    }

    /**
     * @Route("dashboard/eleve/examen", name="exam_eleve_index")
     */
    public function examEleveIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();

        $exams = $this->getDoctrine()->getManager()->getRepository(Examen::class)->findAll();

        return $this->render('examen/indexEleve.html.twig', [
            'exams' => $exams,
        ]);
    }

    /**
     * @Route("dashboard/eleve/examen/view/{id}", name="exam_eleve_show")
     */
    public function exerciceEleveShow(Examen $exam, Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();
        $exams = $this->getDoctrine()->getManager()->getRepository(Examen::class)->findBy(['matiere' => $exam->getMatiere()]);

//        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        $comments = $exam->getComments();

        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setExam($exam);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('examen/showEleve.html.twig', [
            'exam' => $exam,
            'comment' => $comment,
            'commentform' => $form->createView(),
            'exams' => $exams,
            'comments' => $comments,
        ]);
    }
}

