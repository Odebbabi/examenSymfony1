<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Examen;
use App\Entity\Exercice;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Quiz;
use App\Entity\User;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class DashboardController extends AbstractController
{


    /**
     * @Route("/", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/admin", name="dashboard_admin")
     */
    public function dashboardAdmin(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $maitres = count($em->getRepository(User::class)->findBy(['accountType' => 'MAITRE'])) ;
        $eleves = count($em->getRepository(User::class)->findBy(['accountType' => 'ELEVE'])) ;
        $niveaux = count($em->getRepository(Niveau::class)->findAll());
        $matieres = count($em->getRepository(Matiere::class)->findAll());


        return $this->render('dashboard/dashboardAdmin.html.twig', [
            'controller_name' => 'DashboardController',
            'utilisateur'=> $user,
            'maitres' => $maitres,
            'eleves' => $eleves,
            'niveaux'=> $niveaux,
            'matieres'=> $matieres,





        ]);
    }

    /**
     * @Route("/dashboard/eleve", name="dashboard_user_eleve")
     */
    public function dashboardUserEleve(): Response
    {

        if ($this->getUser()->getAccountType() === 'MAITRE')
            throw new AccessDeniedException();

        return $this->render('dashboard/dashboardEleve.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/maitre", name="dashboard_user_maitre")
     */
    public function dashboardUserMaitre(): Response
    {
        if ($this->getUser()->getAccountType() === 'ELEVE')
            throw new AccessDeniedException();

        $user = $this->getUser();


        return $this->render('dashboard/dashboardMaitre.html.twig', [
            'controller_name' => 'DashboardController',
            'user'=> $user,

        ]);
    }

    /**
     * @Route("/check-role", name="dashboard_check_role")
     */
    public function dashboardCheckRole(): Response
    {
        $role = $this->getUser()->getRoles();
        if (!empty($role) && $role[0] == 'ROLE_ADMIN')
            return $this->redirectToRoute('dashboard_admin');
        if (!empty($role) && $role[0] == 'ROLE_USER')
        {
            if ($this->getUser()->getAccountType() == 'ELEVE')
                return $this->redirectToRoute('dashboard_user_eleve');
            elseif ($this->getUser()->getAccountType() == 'MAITRE')
                return $this->redirectToRoute('dashboard_user_maitre');
        }
        return $this->redirectToRoute('app_login');
    }
}
