<?php

namespace App\Controller\User\Parent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user/parent", name="user_parent_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('user/parent/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
