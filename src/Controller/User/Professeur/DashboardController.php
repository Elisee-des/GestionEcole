<?php

namespace App\Controller\User\Professeur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user/professeur", name="user_professeur_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('user/professeur/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
