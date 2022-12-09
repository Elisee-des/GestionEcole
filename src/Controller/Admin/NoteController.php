<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Note;
use App\Entity\Trimestre;
use App\Form\Note\CreerNoteType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/note", name="admin_note_")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="annee")
     */
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        return $this->render('admin/note/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/liste/trimestre/{id}", name="liste_trimestre")
     */
    public function listeTrimestre(Annee $annee): Response
    {
        $trimestres = $annee->getTrimestres();

        return $this->render('admin/note/trimestre/listeTrimestre.html.twig', [
            'trimestres' => $trimestres,
        ]);
    }

    /**
     * @Route("/liste/classe/{id}", name="liste_classe")
     */
    public function listeClasses(Trimestre $trimestre, ClasseRepository $classeRepository): Response
    {
        $idAnnee = $trimestre->getAnnee()->getId();

        // dd($annee->getId());
        $classes = $classeRepository->getClasses($idAnnee);

        return $this->render('admin/note/classe/listeClasse.html.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @Route("/liste/matieres/{id}", name="liste_matiere")
     */
    public function listeMatiere(Classe $classe): Response
    {

        $matieres = $classe->getMatieres();

        return $this->render('admin/note/matiere/listeMatieres.html.twig', [
            'matieres' => $matieres,
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/liste/eleves/{id}", name="liste_eleve")
     */
    public function listeEleves(Classe $classe): Response
    {
        $eleves = $classe->getEleves();

        return $this->render('admin/note/listeEleves.html.twig', [
            'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $note = new Note();

        $form = $this->createForm(CreerNoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "La note de " . $note->getNote() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_note_liste');
        }

        return $this->render('admin/note/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, note $note): Response
    {

        $form = $this->createForm(EditernoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_note")["nom"];

            $note->setNom($salle);

            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une note. La nouvelle note est " . $note->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_note_liste');
        }

        return $this->render('admin/note/editer.html.twig', [
            'form' => $form->createView(),
            'note' => $note
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, note $note): Response
    {

        $entityManager->remove($note);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "note supprimer avec succes"
        );

        return $this->redirectToRoute('admin_note_liste');
    }
}
