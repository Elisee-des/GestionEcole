<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Form\Classe\CreerClasseListeType;
use App\Form\Classe\CreerClasseMatiereType;
use App\Form\Classe\CreerClasseType;
use App\Form\Classe\EditerClasseListeType;
use App\Form\Classe\EditerClasseType;
use App\Repository\AnneeRepository;
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
     * @Route("/", name="annee")
     */
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        return $this->render('admin/classe/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/listeDesClasse/{id}", name="liste_des_classes")
     */
    public function listeClasse(Annee $annee): Response
    {
        // $annees = $anneeRepository->findAll();

        return $this->render('admin/classe/listeClasse.html.twig', [
            'classes' => $annee->getClasses(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/listeDesEleve/{id}", name="liste_des_eleves")
     */
    public function listeEleve(Classe $classe): Response
    {
        // $annees = $anneeRepository->findAll();

        return $this->render('admin/classe/listeEleves.html.twig', [
            'eleves' => $classe->getEleves(),
            'classe' => $classe
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
     * @Route("/creer/{id}", name="creer_classe_dans_liste")
     */
    public function creerClasseListe(EntityManagerInterface $entityManager, Request $request, Annee $annee): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseListeType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_classe_liste")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($annee);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Classe " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste_des_classes', ["id" => $annee->getId()]);
        }

        return $this->render('admin/classe/creerClasseListe.html.twig', [
            'form' => $form->createView(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer_classe_dans_liste")
     */
    public function editerClasseListe(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {
        $form = $this->createForm(EditerClasseListeType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_classe_liste")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($classe->getAnnee());

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Classe " . $classe->getNom() . " a ete modifié avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste_des_classes', ["id" => $classe->getAnnee()->getId()]);
        }

        return $this->render('admin/classe/editerClasse.html.twig', [
            'form' => $form->createView(),
            'classe' => $classe
        ]);
    }


    /**
     * @Route("/creerPourMatiere/{id}", name="creer_pour_matiere")
     */
    public function creerPourMatiere(EntityManagerInterface $entityManager, Request $request, Annee $annee): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseMatiereType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_classe")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($annee);

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
            // 'classe' => $classe
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
        if (!$classe) {
            throw $this->createNotFoundException(
                "Cette classe n'existe pas"
            );
        }

        $entityManager->remove($classe);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Classe supprimer avec succes"
        );

        return $this->redirectToRoute('admin_classe_liste');
    }
}
