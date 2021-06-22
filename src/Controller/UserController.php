<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EleveFormType;
use App\Form\MaitreFormType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/maitre", name="user_maitre_index")
     */
    public function maitreIndex(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findBy(['accountType' => 'MAITRE']);

        $user = $this->getUser();

        return $this->render('user/maitre/index.html.twig', [
            'users' => $users,
            'utilisateur' => $user
        ]);
    }

    /**
     * @Route("/admin/maitre/view/{id}", name="user_maitre_show")
     */
    public function maitreShow(User $user): Response
    {
        $us = $this->getUser();
        return $this->render('user/maitre/show.html.twig', [
            'user' => $user,
            'utilisateur'=> $us,
        ]);
    }

    /**
     * @Route("/admin/maitre/edit/{id}", name="user_maitre_edit")
     */
    public function maitreEdit(Request $request, User $user): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(MaitreFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail! maître modifié avec succès ');

            return $this->redirectToRoute( 'user_maitre_index');
        }
        $us = $this->getUser();

        return $this->render('user/maitre/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("/admin/maitre/delite/{id}", name="user_maitre_delete")
     */
    public function maitreDelete(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success',' Bon travail! maître supprimé avec succès ');

        return $this->redirectToRoute('user_maitre_index');

    }

    /**
     * @Route("/admin/maitre/verify/{id}", name="user_maitre_verify")
     */
    public function maitreVerify(User $user): Response
    {
        $user->setIsVerified(!$user->isVerified());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_maitre_index');
    }


    /**
     * @Route("/admin/eleve", name="user_eleve_index")
     */
    public function eleveIndex(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findBy(['accountType' => 'ELEVE']);

        $us = $this->getUser();

        return $this->render('user/eleve/index.html.twig', [
            'users' => $users,
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("/admin/eleve/view/{id}", name="user_eleve_show")
     */
    public function eleveShow(User $user): Response
    {
        $us = $this->getUser();

        return $this->render('user/eleve/show.html.twig', [
            'user' => $user,
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("/admin/eleve/edit/{id}", name="user_eleve_edit")
     */
    public function eleveEdit(Request $request, User $user): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EleveFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail! élève modifié avec succès ');

            return $this->redirectToRoute('user_eleve_index');
        }
        $us = $this->getUser();

        return $this->render('user/eleve/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("/admin/eleve/delite/{id}", name="user_eleve_delete")
     */
    public function eleveDelete(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success',' Bon travail! élève supprimé avec succès ');

        return $this->redirectToRoute('user_eleve_index');
    }

    /**
     * @Route("/admin/eleve/verify/{id}", name="user_eleve_verify")
     */
    public function eleveVerify(User $user): Response
    {
        $user->setIsVerified(!$user->isVerified());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_eleve_index');
    }
}
