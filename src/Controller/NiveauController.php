<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Form\NiveauFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NiveauController extends AbstractController
{

    /**
     * @Route("/admin/niveau", name="niveau_index")
     */
    public function niveauIndex(): Response
    { $niveaux = $this->getDoctrine()->getManager()->getRepository(Niveau::class)->findAll();

        $us = $this->getUser();

        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveaux,
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("/admin/niveau/add", name="niveau_add")
     */
    public function niveauAdd (Request $request ): Response
    {
        $niveau = new Niveau();
        $form = $this->createForm(NiveauFormType::class,$niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            $this->addFlash('success',' Bon travail! niveau ajouté avec succès ');


            return $this->redirectToRoute('niveau_index');
        }
        $us = $this->getUser();

        return $this->render('niveau/Add.html.twig', [
            'niveau' => $niveau,
            'niveauform' => $form->createView(),
            'utilisateur'=> $us,


        ]);
    }

    /**
     * @Route("/admin/niveau/view/{id}", name="niveau_show")
     */
    public function niveauShow(Niveau $niveau): Response
    {
        $us = $this->getUser();

        return $this->render('niveau/show.html.twig', [
            'niveau' => $niveau,
            'utilisateur'=> $us,


        ]);
    }

    /**
     * @Route("/admin/niveau/edit/{id}", name="niveau_edit")
     */
    public function maitreEdit(Request $request, Niveau $niveau): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(NiveauFormType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail! niveau modifié avec succès ');

            return $this->redirectToRoute( 'niveau_index');
        }
        $us = $this->getUser();

        return $this->render('niveau/edit.html.twig', [
            'niveau' => $niveau,
            'niveauform' => $form->createView(),
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("niveau/delite/{id}", name="niveau_delete")
     */
    public function maitreDelete(Niveau $niveau): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($niveau);
        $em->flush();
        $this->addFlash('success',' Bon travail! niveau supprimé avec succès ');

        return $this->redirectToRoute('niveau_index');

    }
}
