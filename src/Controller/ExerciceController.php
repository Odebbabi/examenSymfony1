<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Exercice;
use App\Form\CommentFormType;
use App\Form\ExerciceFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class ExerciceController extends AbstractController
{

    /**
     * @Route("dashboard/maitre/exercice", name="exercice_index")
     */
    public function exerciceIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $exercices = $this->getDoctrine()->getManager()->getRepository(Exercice::class)->findAll();
        $user = $this->getUser();


        return $this->render('exercice/index.html.twig', [
            'exercices' => $exercices,
            'user'=> $user,
        ]);
    }

    /**
     * @Route("dashboard/maitre/exercice/add", name="exercice_add")
     */
    public function exerciceAdd (Request $request ): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $exercice = new Exercice();
        $user = $this->getUser();
        $exercice->setUser($user);


        $form = $this->createForm(ExerciceFormType::class,$exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($exercice);
            $em->flush();
            $this->addFlash('success',' Bon travail! exercice ajouté avec succès ');


            return $this->redirectToRoute('exercice_index');
        }
        $user = $this->getUser();

        return $this->render('exercice/add.html.twig', [
            'exercice' => $exercice,
            'exerciceform' => $form->createView(),
            'user'=> $user,


        ]);
    }
    /**
     * @Route("dashboard/maitre/exercice/view/{id}", name="exercice_show")
     */
    public function exerciceShow(Exercice $exercice): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();
        $user = $this->getUser();

        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
            'user'=> $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/exercice/edit/{id}", name="exercice_edit")
     */
    public function exerciceEdit(Request $request, Exercice $exercice): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ExerciceFormType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail! exercice modifié avec succès ');

            return $this->redirectToRoute( 'exercice_index');
        }
        $user = $this->getUser();

        return $this->render('exercice/edit.html.twig', [
            'exercice' => $exercice,
            'exerciceform' => $form->createView(),
            'user'=> $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/exercice/delite/{id}", name="exercice_delete")
     */
    public function exerciceDelete(Exercice $exercice): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($exercice);
        $em->flush();
        $this->addFlash('success',' Bon travail! exercice supprimé avec succès ');

        return $this->redirectToRoute('exercice_index');

    }

    /**
     * @Route("dashboard/eleve/exercice", name="exercice_eleve_index")
     */
    public function exerciceEleveIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();

        $exercices = $this->getDoctrine()->getManager()->getRepository(Exercice::class)->findAll();

        return $this->render('exercice/indexEleve.html.twig', [
            'exercices' => $exercices,
        ]);
    }

    /**
     * @Route("dashboard/eleve/exercice/view/{id}", name="exercice_eleve_show")
     */
    public function exerciceEleveShow(Exercice $exercice, Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();
        $exercices = $this->getDoctrine()->getManager()->getRepository(Exercice::class)->findBy(['cour' => $exercice->getCour()]);

//        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        $comments = $exercice->getComments();

        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setExercice($exercice);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('exercice/showEleve.html.twig', [
            'exercice' => $exercice,
            'comment' => $comment,
            'commentform' => $form->createView(),
            'exercices' => $exercices,
            'comments' => $comments,
        ]);
    }

}

