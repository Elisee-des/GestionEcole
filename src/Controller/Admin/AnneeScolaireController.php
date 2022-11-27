<?php

namespace App\Controller\Admin;

use App\Entity\AnneeScolaire;
use App\Form\AnneeScolaire\AnneeScolaireType;
use App\Form\AnneeScolaire\EditerAnneeScolaireType;
use App\Repository\AnneeScolaireRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin/annee_scolaire", name="admin_annee_scolaire_")
 */
class AnneeScolaireController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(AnneeScolaireRepository $anneeScolaireRepository): Response
    {
        $anneeScolaires = $anneeScolaireRepository->findAll();

        return $this->render('admin/annee_scolaire/index.html.twig', [
            'anneescolaires' => $anneeScolaires,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $anneeScolaire = new AnneeScolaire();

        $form = $this->createForm(AnneeScolaireType::class, $anneeScolaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annee = $request->get("annee_scolaire")["annee"];
            $description = $request->get("annee_scolaire")["description"];

            $anneeScolaire->setAnnee($annee);
            $anneeScolaire->setDescription($description);

            $entityManager->persist($anneeScolaire);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Annee " . $anneeScolaire->getAnnee() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_annee_scolaire_liste');
        }

        return $this->render('admin/annee_scolaire/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, AnneeScolaire $anneeScolaire): Response
    {

        $form = $this->createForm(EditerAnneeScolaireType::class, $anneeScolaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annee = $request->get("editer_annee_scolaire")["annee"];
            $description = $request->get("editer_annee_scolaire")["description"];

            $anneeScolaire->setAnnee($annee);
            $anneeScolaire->setDescription($description);

            $entityManager->persist($anneeScolaire);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une annee. La nouvelle annee est " . $anneeScolaire->getAnnee() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_annee_scolaire_liste');
        }

        return $this->render('admin/annee_scolaire/editer.html.twig', [
            'form' => $form->createView(),
            'anneeScolaire' => $anneeScolaire
        ]);
    }

     /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, AnneeScolaire $anneeScolaire): Response
    {

            $entityManager->remove($anneeScolaire);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Annee scolaire supprimer avec succes"
            );

            return $this->redirectToRoute('admin_annee_scolaire_liste');

    }
}
