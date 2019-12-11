<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\Artist\SearchFormType;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artist", name="artist_")
 */
class ArtistController extends AbstractController
{
    /**
     * URI: /artist-list
     * Nom: artist_list
     * @Route("-list", name="list")
     */
    public function index(Request $request, ArtistRepository $artistRepository)
    {
        //Création du formulaire
        $form = $this->createForm(SearchFormType::class);
        // Traitement de la requeet par le formulaire
        $form->handleRequest($request);

        // Si le formulaire est envoyé & valide
        if ($form->isSubmitted() && $form->isValid()) {
            $recherche = $form->getData()['name'];

            $list = $artistRepository->searchByName($recherche);
            $title = sprintf('Résultats pour "%s"', $recherche);
        }
        else{
            $list = $artistRepository->findAll();
            $title = 'Artistes' ;

        }

        return $this->render('artist/list.html.twig', [
            'artist_list' => $list,
            'title' => $title,
            'search_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="page")
     */
    public function page(Artist $artist)
    {
        return $this->render('artist/artist_page.html.twig', [
            'artist' => $artist
        ]);
    }
}