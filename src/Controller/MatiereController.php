<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MatiereController extends AbstractController
{


    /**
     * @Route("admin/matiere", name="matiere_index")
     */
    public function matiereindex(): Response
    {
        $us = $this->getUser();

        $matieres = $this->getDoctrine()->getManager()->getRepository(Matiere::class)->findAll();
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("admin/matiere/add", name="matiere_add")
     */
    public function matiereAdd (Request $request ): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereFormType::class,$matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();
            $this->addFlash('success',' Bon travail! matière ajouté avec succès ');


            return $this->redirectToRoute('matiere_index');
        }
        $us = $this->getUser();

        return $this->render('matiere/add.html.twig', [
            'matiere' => $matiere,
            'matiereform' => $form->createView(),
            'utilisateur'=> $us,


        ]);
    }

    /**
     * @Route("admin/matiere/view/{id}", name="matiere_show")
     */
    public function matiereShow(Matiere $matiere): Response
    {
        $us = $this->getUser();

        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("admin/matiere/edit/{id}", name="matiere_edit")
     */
    public function maitreEdit(Request $request, Matiere $matiere): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(MatiereFormType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success',' Bon travail ! matière modifié avec succès ');

            return $this->redirectToRoute( 'matiere_index');
        }
        $us = $this->getUser();

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'matiereform' => $form->createView(),
            'utilisateur'=> $us,

        ]);
    }

    /**
     * @Route("admin/matiere/delite/{id}", name="matiere_delete")
     */
    public function maitreDelete(Matiere $matiere): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiere);
        $em->flush();

        $this->addFlash('success',' Bon travail ! matière supprimé avec succès ');

        return $this->redirectToRoute('matiere_index');

    }
}
