<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Cour;
use App\Form\CommentFormType;
use App\Form\CourFormType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class CourController extends AbstractController
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
     * @Route("dashboard/maitre/cour", name="cour_index")
     */
    public function courIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();
        $cours = $this->getDoctrine()->getManager()->getRepository(Cour::class)->findAll();
        $user = $this->security->getUser();

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }

    /**
     * @Route("dashboard/maitre/cour/add", name="cour_add")
     */
    public function courAdd(Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $cour = new Cour();
        $user = $this->security->getUser();

        $form = $this->createForm(CourFormType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setUser($user);
            $em->persist($cour);
            $em->flush();

            return $this->redirectToRoute('cour_index');
        }
        $user = $this->security->getUser();

        return $this->render('cour/add.html.twig', [
            'cour' => $cour,
            'courform' => $form->createView(),
            'user' => $user,


        ]);
    }

    /**
     * @Route("dashboard/maitre/cour/view/{id}", name="cour_show")
     */
    public function courShow(Cour $cour): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $user = $this->security->getUser();

        return $this->render('cour/show.html.twig', [
            'cour' => $cour,
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/cour/edit/{id}", name="cour_edit")
     */
    public function courEdit(Request $request, Cour $cour): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CourFormType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('cour_index');
        }
        $user = $this->security->getUser();

        return $this->render('cour/edit.html.twig', [
            'cour' => $cour,
            'courform' => $form->createView(),
            'user' => $user,

        ]);
    }

    /**
     * @Route("dashboard/maitre/cour/delite/{id}", name="cour_delete")
     */
    public function courDelete(Cour $cour): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($cour);
        $em->flush();
        return $this->redirectToRoute('cour_index');

    }

    /**
     * @Route("dashboard/eleve/cours", name="cour_eleve_index")
     */
    public function courEleveIndex(): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();
        $cours = $this->getDoctrine()->getManager()->getRepository(Cour::class)->findAll();

        return $this->render('cour/indexEleve.html.twig', [
            'cours' => $cours,
        ]);
    }

    /**
     * @Route("dashboard/eleve/cour/view/{id}", name="cour_eleve_show")
     */
    public function courEleveShow(Cour $cour, Request $request): Response
    {
        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();
        $cours = $this->getDoctrine()->getManager()->getRepository(Cour::class)->findBy(['matiere' => $cour->getMatiere()]);

//        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        $comments = $cour->getComments();
        $em = $this->getDoctrine()->getManager();


        $comment = new Comment();
        $user = $this->security->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setCour($cour);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('cour/showEleve.html.twig', [
            'cour' => $cour,
            'comment' => $comment,
            'commentform' => $form->createView(),
            'cours' => $cours,
            'comments' => $comments,
        ]);
    }


//    /**
//     * @Route("dashboard/eleve/cour", name="cour_index")
//     */
//    public function courIndex(): Response
//    {
//        if ($this->getUser()->getAccountType() === 'ELEVE')
//            throw new AccessDeniedException();
//        $cours = $this->getDoctrine()->getManager()->getRepository(Cour::class)->findAll();
//
//        return $this->render('cour/index.html.twig', [
//            'cours' => $cours,
//        ]);
//    }

}
