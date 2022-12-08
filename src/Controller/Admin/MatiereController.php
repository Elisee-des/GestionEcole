<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Form\Classe\CreerClasseType;
use App\Form\Matiere\CreerMatiereType;
use App\Form\Matiere\EditerMatiereType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
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
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        return $this->render('admin/matiere/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="annee_detail")
     */
    public function annee(Annee $annee): Response
    {
        // dd($annee);
        // $annees = $anneeRepository->findAll();

        return $this->render('admin/matiere/detailAnnee.html.twig', [
            'classes' => $annee->getClasses(),
            'classeFontionAnnee' => $annee
        ]);
    }

    // /**
    //  * @Route("/creerPourMatiere/{id}", name="creer_classe")
    //  */
    // public function creerPourMatiere(EntityManagerInterface $entityManager, Request $request): Response
    // {
    //     $classe = new Classe();

    //     $form = $this->createForm(CreerClasseType::class, $classe);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $nom = $request->get("creer_classe")["nom"];

    //         $classe->setNom($nom);

    //         $entityManager->persist($classe);
    //         $entityManager->flush();

    //         $this->addFlash(
    //             'success',
    //             "Classe " . $classe->getNom() . " a ete ajouter avec succes"
    //         );

    //         return $this->redirectToRoute('admin_matiere_liste');
    //     }

    //     return $this->render('admin/matiere/creerClasse.html.twig', [
    //         'form' => $form->createView(),
    //         'annee' => $classe->getAnnee()
    //     ]);
    // }

    // /**
    //  * @Route("/detail/{id}", name="detail")
    //  */
    // public function detail(Matiere $matiere): Response
    // {
    //     // $matieres = $matiereRepository->findAll();

    //     return $this->render('admin/matiere/detail.html.twig', [
    //         'matiere' => $matiere,
    //     ]);
    // }

    /**
     * @Route("/creer/{id}", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {
        $matiere = new Matiere();

        $form = $this->createForm(CreerMatiereType::class, $matiere);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_matiere")["nom"];

            $matiere->setNom($nom);
            // $matiere->set($classe->getId());
            $matiere->setClasse($classe->getId());

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
            'classe' => $classe
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
            $matiere->setClasse($matiere->getClasse());

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
