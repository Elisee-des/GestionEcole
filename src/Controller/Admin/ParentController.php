<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreerParentType;
use App\Form\EditerParentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/parent", name="admin_parent_")
 */
class ParentController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(UserRepository $userRepository): Response
    {
        $parents = $userRepository->findAll();

        return $this->render('admin/parent/index.html.twig', [
            'parents' => $parents,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detai(User $parent): Response
    {

        return $this->render('admin/parent/detail.html.twig', [
            'parent' => $parent,
        ]);
    }


    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordhasher): Response
    {
        $parent = new User();

        $form = $this->createForm(CreerParentType::class, $parent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = ["ROLE_PARENT"];

            $nom = $request->get("creer_parent")["nom"];
            $prenom = $request->get("creer_parent")["prenom"];
            $numero = $request->get("creer_parent")["numero"];
            $passwordShow = $request->get("creer_parent")["password"]["first"];
            $password = $passwordhasher->hashPassword($parent, $passwordShow);
            $email = $request->get("creer_parent")["email"];

            $parent->setNom($nom);
            $parent->setPrenom($prenom);
            $parent->setRoles($role);
            $parent->setIsparent(true);
            $parent->setNumero($numero);
            $parent->setPassword($password);
            $parent->setEmail($email);

            $entityManager->persist($parent);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le parent ' . $parent->getNom() . ' ' .  $parent->getPrenom() . " a ete ajouter avec succes a la liste des parents"
            );

            return $this->redirectToRoute('admin_parent_liste');
        }

        return $this->render('admin/parent/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, User $parent): Response
    {
        $form = $this->createForm(EditerParentType::class, $parent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_parent")["nom"];
            $prenom = $request->get("editer_parent")["prenom"];
            $numero = $request->get("editer_parent")["numero"];
            $email = $request->get("editer_parent")["email"];

            $parent->setNom($nom);
            $parent->setPrenom($prenom);
            $parent->setNumero($numero);
            $parent->setEmail($email);

            $entityManager->persist($parent);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le parent ' . $parent->getNom() . ' ' .  $parent->getPrenom() . " a ete modifier avec succes a la liste des parents"
            );

            return $this->redirectToRoute('admin_parent_liste');
        }

        return $this->render('admin/parent/editer.html.twig', [
            'form' => $form->createView(),
            'parent' => $parent
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, User $parent): Response
    {

        $entityManager->remove($parent);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Le parent a été supprimer avec succes"
        );

        return $this->redirectToRoute('admin_parent_liste');
    }
}
