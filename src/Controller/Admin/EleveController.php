<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Form\Eleve\CreerEleveDansClasseType;
use App\Form\Eleve\CreerEleveType;
use App\Form\Eleve\EditerDansEleveDetailType;
use App\Form\Eleve\EditerEleveType;
use App\Repository\AnneeRepository;
use App\Repository\EleveRepository;
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
    public function index(AnneeRepository $anneeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        return $this->render('admin/eleve/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/annee/{id}", name="annee_detail")
     */
    public function detail(Annee $annee): Response
    {

        return $this->render('admin/eleve/detailAnnee.html.twig', [
            'classes' => $annee->getClasses(),
            'annee' => $annee
        ]);
    }


    /**
     * @Route("/classe/{id}/detail", name="annee_classe_detail")
     */
    public function detailClasse(Classe $classe): Response
    {

        return $this->render('admin/eleve/detailClasse.html.twig', [
            'eleves' => $classe->getEleves(),
            'classe' => $classe,
            'classeId' => $classe->getAnnee()->getId()
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detailEleve(ELeve $eleve): Response
    {

        return $this->render('admin/eleve/detailEleve.html.twig', [
            // 'eleves' => $classe->getEleves(),
            'eleve' => $eleve,
            'classe' => $eleve->getClasse()->getId()
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
            $nom = $request->get("creer_eleve")["nom"];
            $prenom = $request->get("creer_eleve")["prenom"];
            $numero = $request->get("creer_eleve")["numero"];
            $email = $request->get("creer_eleve")["email"];
            $classe = $eleve->getClasse();
            $annee = $eleve->getAnnee();
            $parent = $eleve->getUser();
            $eleve->setNom($nom);
            $eleve->setPrenom($prenom);
            $eleve->setNumero($numero);
            $eleve->setEmail($email);
            $eleve->setAnnee($annee);
            $eleve->setClasse($classe);
            $eleve->setUser($parent);

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
     * @Route("/creerDansClasse/{id}", name="creer_dans_classe")
     */
    public function creerDansClasee(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {
        $eleve = new Eleve();

        $form = $this->createForm(CreerEleveDansClasseType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_eleve_dans_classe")["nom"];
            $prenom = $request->get("creer_eleve_dans_classe")["prenom"];
            $numero = $request->get("creer_eleve_dans_classe")["numero"];
            $email = $request->get("creer_eleve_dans_classe")["email"];
            $parent = $eleve->getUser();
            $annee = $classe->getAnnee();
            $eleve->setNom($nom);
            $eleve->setPrenom($prenom);
            $eleve->setNumero($numero);
            $eleve->setEmail($email);
            $eleve->setClasse($classe);
            $eleve->setAnnee($annee);
            $eleve->setUser($parent);
            // dd($nom, $prenom, $numero, $email, $parent, $classe, $annee);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " " . $eleve->getPrenom() . " a ete ajouter avec succes dans cette salle de classe"
            );

            return $this->redirectToRoute('admin_eleve_annee_classe_detail', ["id" => $classe->getId()]);
        }

        return $this->render('admin/eleve/creerDansClasse.html.twig', [
            'form' => $form->createView(),
            "classe" => $classe
        ]);
    }


    /**
     * @Route("/EditerDansEleveDetail/{id}", name="editer_dans_eleve_detail")
     */
    public function editerDansDetailEleve(EntityManagerInterface $entityManager, Request $request, Eleve $eleve): Response
    {

        $form = $this->createForm(EditerDansEleveDetailType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_dans_eleve_detail")["nom"];
            $prenom = $request->get("editer_dans_eleve_detail")["prenom"];
            $numero = $request->get("editer_dans_eleve_detail")["numero"];
            $email = $request->get("editer_dans_eleve_detail")["email"];
            $parent = $eleve->getUser();
            $annee = $eleve->getAnnee();
            $classe = $eleve->getClasse();
            $eleve->setNom($nom);
            $eleve->setPrenom($prenom);
            $eleve->setNumero($numero);
            $eleve->setEmail($email);
            $eleve->setClasse($classe);
            $eleve->setAnnee($annee);
            $eleve->setUser($parent);
            // dd($nom, $prenom, $numero, $email, $parent, $classe, $annee);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " " . $eleve->getPrenom() . " a ete Modifier avec succes dans cette salle de classe"
            );

            return $this->redirectToRoute('admin_eleve_detail', ["id" => $eleve->getId()]);
        }

        return $this->render('admin/eleve/editerDansEleveDetail.html.twig', [
            'form' => $form->createView(),
            'eleve' => $eleve
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Eleve $eleve): Response
    {
        $form = $this->createForm(EditerEleveType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $nom = $request->get("editer_eleve")["nom"];
            $prenom = $request->get("editer_eleve")["prenom"];
            $numero = $request->get("editer_eleve")["numero"];
            $email = $request->get("editer_eleve")["email"];
            $classe = $eleve->getClasse();
            $annee = $eleve->getAnnee();
            $parent = $eleve->getUser();

            $eleve->setNom($nom);
            $eleve->setPrenom($prenom);
            $eleve->setNumero($numero);
            $eleve->setEmail($email);
            $eleve->setAnnee($annee);
            $eleve->setClasse($classe);
            $eleve->setUser($parent);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " a ete modifier avec succes"
            );

            return $this->redirectToRoute('admin_eleve_annee_classe_detail', ["id" => $eleve->getClasse()->getId()]);
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
            "eleve supprimer avec succes"
        );

        return $this->redirectToRoute('admin_eleve_annee_classe_detail', ["id" => $eleve->getClasse()->getId()]);
    }
}
