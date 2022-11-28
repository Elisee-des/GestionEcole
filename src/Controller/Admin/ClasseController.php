<?php

namespace App\Controller\Admin;

use App\Entity\Classe;
use App\Form\Classe\CreerClasseType;
use App\Form\Classe\EditerClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/classe", name="admin_classe_")
 */
class ClasseController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(ClasseRepository $ClasseRepository): Response
    {
        $Classes = $ClasseRepository->findAll();

        return $this->render('admin/classe/index.html.twig', [
            'classes' => $Classes,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("creer_classe")["nom"];

            $classe->setNom($salle);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "classe " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste');
        }

        return $this->render('admin/classe/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {

        $form = $this->createForm(EditerClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_classe")["nom"];

            $classe->setNom($salle);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une classe. La nouvelle classe est " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste');
        }

        return $this->render('admin/classe/editer.html.twig', [
            'form' => $form->createView(),
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Classe $classe): Response
    {

        $entityManager->remove($classe);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "classe supprimer avec succes"
        );

        return $this->redirectToRoute('admin_classe_liste');
    }
}
