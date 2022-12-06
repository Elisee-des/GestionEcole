<?php

namespace App\Controller\Admin;

use App\Entity\Matiere;
use App\Form\Matiere\CreerMatiereType;
use App\Form\Matiere\EditerMatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/matiere", name="admin_matiere_")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findAll();

        return $this->render('admin/matiere/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    /**
     * @Route("/detail", name="detail")
     */
    public function detail(Matiere $matiere): Response
    {
        // $matieres = $matiereRepository->findAll();

        return $this->render('admin/matiere/index.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $matiere = new Matiere();

        $form = $this->createForm(CreerMatiereType::class, $matiere);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_matiere")["nom"];

            $matiere->setNom($nom);

            $entityManager->persist($matiere);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Matiere " . $matiere->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_matiere_liste');
        }

        return $this->render('admin/matiere/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Matiere $matiere): Response
    {

        $form = $this->createForm(EditerMatiereType::class, $matiere);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_matiere")["nom"];

            $matiere->setNom($nom);

            $entityManager->persist($matiere);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une matiere. La nouvelle matiere est " . $matiere->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_matiere_liste');
        }

        return $this->render('admin/matiere/editer.html.twig', [
            'form' => $form->createView(),
            'matiere' => $matiere
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Matiere $matiere): Response
    {

        $entityManager->remove($matiere);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "matiere supprimer avec succes"
        );

        return $this->redirectToRoute('admin_matiere_liste');
    }
}
