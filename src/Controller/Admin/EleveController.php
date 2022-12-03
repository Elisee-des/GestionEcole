<?php

namespace App\Controller\Admin;

use App\Entity\Eleve;
use App\Repository\EleveRepository;
use App\Form\Eleve\CreerEleveType;
use App\Form\Eleve\EditerEleveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/eleve", name="admin_eleve_")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(EleveRepository $eleveRepository): Response
    {
        $eleves = $eleveRepository->findAll();

        return $this->render('admin/eleve/index.html.twig', [
            'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $eleve = new Eleve();
        
        $form = $this->createForm(CreerEleveType::class, $eleve);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($eleve);
            $elev = $request->get("creer_eleve")["nom"];
            $elev = $request->get("creer_eleve")["nom"];
            $elev = $request->get("creer_eleve")["nom"];
            $elev = $request->get("creer_eleve")["nom"];
            $elev = $request->get("creer_eleve")["nom"];

            $eleve->setNom($elev);
            $eleve->setNom($elev);
            $eleve->setNom($elev);
            $eleve->setNom($elev);
            $eleve->setNom($elev);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_eleve_liste');
        }

        return $this->render('admin/eleve/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, eleve $eleve): Response
    {

        $form = $this->createForm(EditerEleveType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_eleve")["nom"];

            $eleve->setNom($salle);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une eleve. La nouvelle eleve est " . $eleve->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_eleve_liste');
        }

        return $this->render('admin/eleve/editer.html.twig', [
            'form' => $form->createView(),
            'eleve' => $eleve
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Eleve $eleve): Response
    {

        $entityManager->remove($eleve);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Eleve supprimer avec succes"
        );

        return $this->redirectToRoute('admin_eleve_liste');
    }
}
