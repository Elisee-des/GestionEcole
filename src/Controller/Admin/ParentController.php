<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreerParentType;
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
                'Le parent ' .$parent->getNom() . ' ' .  $parent->getPrenom(). " a ete ajouter avec succes a la liste des parents"
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

        return $this->render('admin/annee_scolaire/creer.html.twig', [
            'form' => $form->createView(),
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
