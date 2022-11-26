<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/admin/test", name="app_test")
     */
    public function index(): Response
    {
        return $this->render('admin/test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
