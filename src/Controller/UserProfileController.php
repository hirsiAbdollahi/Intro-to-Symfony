<?php

namespace App\Controller;

use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 * @IsGranted("ROLE_USER")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("/", name="edit")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        // Création du formulaire en passant les données (l'utilisateur courant)
        $user = $this->getUser();
        $profileForm = $this->createForm(UserProfileFormType::class, $user);
        $profileForm->handleRequest($request);

        // Vérification de validité
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            // Formulaire lié à une classe entité: getData() retourne l'entité
            $user = $profileForm->getData();
            dd($user);
        }

        return $this->render('user/profile.html.twig');
    }
}
