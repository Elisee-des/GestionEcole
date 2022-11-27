<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreerProfesseurType;
use App\Form\EditerMotDePasseProfesseurType;
use App\Form\EditerProfesseurType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/professeur", name="admin_professeur_")
 */
class ProfesseurController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(UserRepository $userRepository): Response
    {
        $professeurs = $userRepository->findAll();

        return $this->render('admin/professeur/index.html.twig', [
            'professeurs' => $professeurs,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detai(User $professeur): Response
    {

        return $this->render('admin/professeur/detail.html.twig', [
            'professeur' => $professeur,
        ]);
    }


    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordhasher): Response
    {
        $professeur = new User();

        $form = $this->createForm(CreerProfesseurType::class, $professeur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = ["ROLE_PROFESSEUR"];

            $nom = $request->get("creer_professeur")["nom"];
            $prenom = $request->get("creer_professeur")["prenom"];
            $numero = $request->get("creer_professeur")["numero"];
            $passwordShow = $request->get("creer_professeur")["password"]["first"];
            $password = $passwordhasher->hashPassword($professeur, $passwordShow);
            $email = $request->get("creer_professeur")["email"];

            $professeur->setNom($nom);
            $professeur->setPrenom($prenom);
            $professeur->setRoles($role);
            $professeur->setIsProf(true);
            $professeur->setNumero($numero);
            $professeur->setPassword($password);
            $professeur->setEmail($email);

            $entityManager->persist($professeur);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le professeur ' . $professeur->getNom() . ' ' .  $professeur->getPrenom() . " a ete ajouter avec succes a la liste des professeurs"
            );

            return $this->redirectToRoute('admin_professeur_liste');
        }

        return $this->render('admin/professeur/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, User $professeur): Response
    {
        $form = $this->createForm(EditerProfesseurType::class, $professeur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_professeur")["nom"];
            $prenom = $request->get("editer_professeur")["prenom"];
            $numero = $request->get("editer_professeur")["numero"];
            $email = $request->get("editer_professeur")["email"];

            $professeur->setNom($nom);
            $professeur->setPrenom($prenom);
            $professeur->setNumero($numero);
            $professeur->setEmail($email);

            $entityManager->persist($professeur);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le professeur ' . $professeur->getNom() . ' ' .  $professeur->getPrenom() . " a ete modifier avec succes a la liste des professeurs"
            );

            return $this->redirectToRoute('admin_professeur_liste');
        }

        return $this->render('admin/professeur/editer.html.twig', [
            'form' => $form->createView(),
            'professeur' => $professeur
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, User $professeur): Response
    {

        $entityManager->remove($professeur);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Le professeur a été supprimer avec succes"
        );

        return $this->redirectToRoute('admin_professeur_liste');
    }

    /**
     * @Route("/{id}/editer_mot_de_passe", name="editer_mot_de_passe")
     */
    public function editerMotDePasse(EntityManagerInterface $entityManagerInterface, Request $request, User $professeur, UserPasswordHasherInterface  $passwordhasher): Response
    {
        $form = $this->createForm(EditerMotDePasseProfesseurType::class, $professeur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordShow = $request->get("editer_mot_de_passe_professeur")["mdp"]["first"];
            $password = $passwordhasher->hashPassword($professeur, $passwordShow);

            $professeur->setPassword($password);

            $entityManagerInterface->persist($professeur);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                'Le mot de passe de ' . $professeur->getNom() . " " . $professeur->getPrenom() . ' a ete modifier avec succes'
            );

            return $this->redirectToRoute('admin_professeur_detail', ["id" => $professeur->getId()]);
        }

        return $this->render('admin/professeur/editerMotDePasee.html.twig', [
            "form" => $form->createView(),
            "professeur" => $professeur
        ]);
    }
}
