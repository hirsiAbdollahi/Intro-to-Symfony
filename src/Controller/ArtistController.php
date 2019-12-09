<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artist;
use App\Form\Artist\SearchFormType;

/**
 * @Route("/artist", name="artist")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("-list", name="_list")
     */

    public function index(ArtistRepository $artistRepository)
    {
        //Creation du formulaire 
        $form = $this->createForm(SearchFormtype::class);

        return $this->render('artist/list.html.twig' , [
            'artist_list' => $artistRepository->findAll(),
            'search_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="_page")
     */

    public function page(Artist $artist)
    {
        return $this->render('artist/artist_page.html.twig', [
            'artist'=>$artist
        ]);
    }    

}
