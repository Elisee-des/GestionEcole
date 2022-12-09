<?php

namespace App\Controller\Admin;

use App\Entity\Abscence;
use App\Form\Abscence\CreerAbscenceType;
use App\Form\Abscence\EditerAbscenceType;
use App\Repository\AbscenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/abscence", name="admin_abscence_")
 */
class AbscenceController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(AbscenceRepository $abscenceRepository): Response
    {
        $abscences = $abscenceRepository->findAll();

        return $this->render('admin/abscence/index.html.twig', [
            'abscences' => $abscences,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $abscence = new Abscence();

        $form = $this->createForm(CreerAbscenceType::class, $abscence);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nbrHeur = $request->get("creer_abscence")["heurAbscence"];

            $abscence->setHeurAbscence($nbrHeur);

            $entityManager->persist($abscence);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "" . $abscence->getHeurAbscence() . " heure(s) abscence a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_abscence_liste');
        }

        return $this->render('admin/abscence/creer.html.twig', [
            'form' => $form->createView(),
            'abscence' => $abscence
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Abscence $abscence): Response
    {

        $form = $this->createForm(EditerAbscenceType::class, $abscence);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nbrHeur = $request->get("editer_abscence")["heurAbscence"];

            $abscence->setHeurAbscence($nbrHeur);
            $abscence->setEleve($abscence->getEleve());

            $entityManager->persist($abscence);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une abscence. La nouvelle abscence est " . $abscence->getHeurAbscence() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_eleve_detail', ["id"=>$abscence->getEleve()->getId()]);
        }

        return $this->render('admin/abscence/editerAbscence.html.twig', [
            'form' => $form->createView(),
            'abscence' => $abscence
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Abscence $abscence): Response
    {

        $entityManager->remove($abscence);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Abscence supprimer avec succes"
        );

        return $this->redirectToRoute('admin_eleve_detail', ["id"=>$abscence->getEleve()->getId()]);
    }
}
