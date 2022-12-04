<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Trimestre;
use App\Form\Trimestre\CreerTrimestreType;
use App\Form\Trimestre\EditerTrimestreType;
use App\Repository\AnneeRepository;
use App\Repository\TrimestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/trimestre", name="admin_trimestre_")
 */
class TrimestreController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();
        // dd($annees);

        return $this->render('admin/trimestre/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Annee $annee): Response
    {

        return $this->render('admin/trimestre/detail.html.twig', [
            'trimestres' => $annee->getTrimestres(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $trimestre = new Trimestre();

        $form = $this->createForm(CreerTrimestreType::class, $trimestre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("creer_trimestre")["nom"];

            $trimestre->setNom($salle);

            $entityManager->persist($trimestre);
            $entityManager->flush();

            $this->addFlash(
                'success',
                " " . $trimestre->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_trimestre_liste');
        }

        return $this->render('admin/trimestre/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, trimestre $trimestre): Response
    {

        $form = $this->createForm(EditerTrimestreType::class, $trimestre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_trimestre")["nom"];

            $trimestre->setNom($salle);

            $entityManager->persist($trimestre);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une trimestre. Le nouvelle trimestre est: " . $trimestre->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_trimestre_detail', ["id"=>$trimestre->getAnnee()->getId()]);
        }

        return $this->render('admin/trimestre/editer.html.twig', [
            'form' => $form->createView(),
            'trimestre' => $trimestre
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, trimestre $trimestre): Response
    {

        $entityManager->remove($trimestre);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Trimestre supprimer avec succes"
        );

        return $this->redirectToRoute('admin_trimestre_detail', ["id"=>$trimestre->getAnnee()->getId()]);
    }
}
