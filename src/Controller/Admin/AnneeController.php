<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Form\Annee\CreerAnneeType;
use App\Form\Annee\EditerAnneeType;
use App\Repository\AnneeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/annee", name="admin_annee_")
 */
class AnneeController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        return $this->render('admin/annee/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $annee = new annee();

        $form = $this->createForm(CreerAnneeType::class, $annee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("creer_annee")["nom"];

            $annee->setNom($salle);

            $entityManager->persist($annee);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "annee " . $annee->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_annee_liste');
        }

        return $this->render('admin/annee/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, annee $annee): Response
    {

        $form = $this->createForm(EditerAnneeType::class, $annee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_annee")["nom"];

            $annee->setNom($salle);

            $entityManager->persist($annee);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une annee. La nouvelle annee est " . $annee->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_annee_liste');
        }

        return $this->render('admin/annee/editer.html.twig', [
            'form' => $form->createView(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, annee $annee): Response
    {

        $entityManager->remove($annee);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "annee supprimer avec succes"
        );

        return $this->redirectToRoute('admin_annee_liste');
    }
}
